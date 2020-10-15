<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Template extends Model
{
    use HasFactory;

    protected $guarded = [];

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

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function updateSlug()
    {
        if (! $this->user_id) {
            throw new \Exception('Trying to generate a slug with no user_id set');
        }
        $this->update([
            'slug' => Str::slug($this->user_id.'-'.$this->name),
        ]);
    }

    public static function makeNew($data)
    {
        if (array_key_exists('team_id', $data)) {
            if ($data['team_id'] == -1) {
                $data['team_id'] = null;
            }
        }
        $job = new static($data);
        $job->uuid = CronUuid::generate();

        return $job;
    }

    public function createNewJob()
    {
        return Cronjob::create([
            'name' => $this->name.' Job '.now()->format('d/m/Y H:i'),
            'uuid' => CronUuid::generate(),
            'user_id' => $this->user_id,
            'grace' => $this->grace,
            'grace_units' => $this->grace_units,
            'period' => $this->period,
            'period_units' => $this->period_units,
            'last_run' => now(),
            'last_alerted' => null,
            'is_silenced' => false,
        ]);
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

    public function getTeamName()
    {
        if ($this->team) {
            return $this->team->name;
        }

        return 'None';
    }

    public function getEmail()
    {
        if ($this->email) {
            return $this->email;
        }

        return $this->user->email;
    }

    public function uri()
    {
        return route('api.template.create_job', $this->slug);
    }
}
