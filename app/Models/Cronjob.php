<?php

namespace App\Models;

use App\Models\CronUuid;
use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cronjob extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'period', 'period_units', 'grace', 'grace_units', 'email', 'is_silenced', 'user_id', 'uuid', 'team_id', 'notes', 'is_logging', 'fallback_email', 'cron_schedule', 'last_run',
    ];
    protected $dates = ['last_run', 'last_alerted'];

    public $units = [
        'minute' => 'Minutes',
        'hour' => 'Hours',
        'day' => 'Days',
        'week' => 'Weeks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pings()
    {
        return $this->hasMany(Ping::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public static function newDefault()
    {
        $job = new static();
        $job->period = 1;
        $job->period_units = 'day';
        $job->grace = 30;
        $job->grace_units = 'minute';

        return $job;
    }

    public static function makeNew($data)
    {
        if ($data['team_id'] == -1) {
            $data['team_id'] = null;
        }
        $job = new static($data);
        $job->uuid = CronUuid::generate();
        $job->last_run = Carbon::now();
        $job->last_alerted = Carbon::now();

        return $job;
    }

    public function getRecentPings()
    {
        return $this->pings()->latest('created_at')->take(20)->get();
    }

    public function isAwol()
    {
        if ($this->cron_schedule) {
            return $this->isAwolViaCron();
        }

        return $this->isAwolViaManual();
    }

    protected function isAwolViaCron()
    {
        $shouldHaveLastRunDate = Carbon::instance(CronExpression::factory($this->cron_schedule)->getPreviousRunDate());
        $shouldNextRunDate = Carbon::instance(CronExpression::factory($this->cron_schedule)->getNextRunDate());
        $periodInMinutes = $shouldNextRunDate->diffInMinutes($shouldHaveLastRunDate);
        $graceMinutes = $this->getGraceInMinutes();
        if (now()->diffInMinutes($this->last_run) > ($periodInMinutes + $graceMinutes)) {
            return true;
        }

        return false;
    }

    protected function isAwolViaManual()
    {
        $now = Carbon::now();
        $graceTime = $this->getGraceTimeInMinutes();
        if ($now->diffInMinutes($this->last_run) > $graceTime) {
            return true;
        }

        return false;
    }

    public function isAlerting()
    {
        if ($this->is_silenced) {
            return false;
        }

        return $this->isAwol();
    }

    public function getGraceTimeInMinutes()
    {
        return $this->getGraceInMinutes() + $this->getPeriodInMinutes();
    }

    public function getGraceInMinutes()
    {
        return $this->convertUnitsToMinutes($this->grace, $this->grace_units);
    }

    public function getPeriodInMinutes()
    {
        return $this->convertUnitsToMinutes($this->period, $this->period_units);
    }

    private function convertUnitsToMinutes($value, $units)
    {
        if ($units == 'minute') {
            return $value;
        }
        if ($units == 'hour') {
            return $value * 60;
        }
        if ($units == 'day') {
            return $value * 60 * 24;
        }
        if ($units == 'week') {
            return $value * 60 * 24 * 7;
        }
        throw new \Exception('Unknown period units');
    }

    public function getEmail()
    {
        if ($this->email) {
            return $this->email;
        }

        return $this->user->email;
    }

    public function getLastRun()
    {
        return $this->last_run ? $this->last_run->format('d/m/Y H:i') : '';
    }

    public function getLastRunDiff()
    {
        return $this->last_run ? $this->last_run->diffForHumans() : '';
    }

    public function getSchedule()
    {
        if ($this->cron_schedule) {
            return $this->cron_schedule;
        }

        return 'Every '.$this->periodIfNotOne().' '.$this->humanPeriodUnits();
    }

    public function periodIfNotOne()
    {
        return $this->period == 1 ? '' : $this->period;
    }

    public function ping($data = '')
    {
        $this->last_run = Carbon::now();
        $this->save();
        if ($this->is_logging) {
            $this->pings()->save(new Ping(['data' => $data]));
        }
    }

    public static function findByUuid($uuid)
    {
        return static::where('uuid', $uuid)->first();
    }

    public function humanPeriodUnits()
    {
        if ($this->period == 1) {
            return ucfirst($this->period_units);
        }

        return ucfirst(Str::plural($this->period_units));
    }

    public function humanGraceUnits()
    {
        if ($this->grace == 1) {
            return ucfirst($this->grace_units);
        }

        return ucfirst(Str::plural($this->grace_units));
    }

    public function hasAlertedRecently()
    {
        $minutesToWait = config('cronmon.alert_interval', 60);
        if ($this->minutesSinceLastAlert() < $minutesToWait) {
            return true;
        }

        return false;
    }

    public function hasntAlertedRecently()
    {
        return ! $this->hasAlertedRecently();
    }

    public function minutesSinceLastAlert()
    {
        return $this->last_alerted->diffInMinutes(Carbon::now());
    }

    public function updateLastAlerted()
    {
        $this->last_alerted = Carbon::now();
        $this->save();
    }

    public function shouldNotifyFallbackAddress()
    {
        if (! $this->fallback_email) {
            return false;
        }
        $triggerDate = $this->last_run->addHours(config('cronmon.fallback_delay', 24));
        if ($triggerDate->gte(now())) {
            return false;
        }

        return true;
    }

    public function uri()
    {
        return route('ping.get', $this->uuid);
    }

    public function getTeamName()
    {
        if ($this->team) {
            return $this->team->name;
        }

        return 'None';
    }

    public function updateFromForm($data)
    {
        if ($data['team_id'] == -1) {
            $data['team_id'] = null;
        }
        $this->fill($data);
        if (array_key_exists('regenerate_uuid', $data)) {
            $this->uuid = CronUuid::generate();
        }
        $this->save();

        return $this;
    }

    public function truncatePings($numberToKeep = 100)
    {
        $keepPings = $this->pings()->latest('created_at')->take($numberToKeep)->pluck('id')->toArray();
        $this->pings()->whereNotIn('id', $keepPings)->delete();
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'is_awol' => $this->isAwol(),
            'show_url' => route('job.show', $this->id),
            'user_url' => route('user.show', $this->user_id),
            'username' => $this->user->username,
            'teamname' => optional($this->team)->name ?? 'None',
            'uri' => $this->uri(),
            'is_silenced' => $this->is_silenced,
            'name' => $this->name,
            'schedule' => $this->getSchedule(),
            'last_run' => $this->getLastRun(),
            'last_run_diff' => $this->getLastRunDiff(),
        ];
    }
}
