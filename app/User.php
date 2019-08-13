<?php

namespace App;

use Illuminate\Support\Str;
use DB;
use Log;
use Storage;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\JobHasGoneAwol;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Notification;

class User extends Authenticatable implements CanResetPassword
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'is_admin', 'is_silenced'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $jobemail;

    public function jobs()
    {
        return $this->hasMany(Cronjob::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function getAvailableJobs()
    {
        return $this->jobs()->orderBy('name')->get();
    }

    public function getTeamJobs()
    {
        $teamIds = $this->teams()->pluck('team_id')->toArray();
        $jobs = Cronjob::whereIn('team_id', $teamIds)->where('user_id', '!=', $this->id)->orderBy('name')->get();
        return $jobs;
    }

    public function getNonSilencedJobs()
    {
        return $this->jobs()->where('is_silenced', false)->get();
    }

    public function addNewJob($data)
    {
        $job = Cronjob::makeNew($data);
        $this->jobs()->save($job);
        return $job;
    }

    public function generateNewApiKey()
    {
        $this->api_key = Str::random(64);
        return $this->api_key;
    }

    /**
     * This loops over all the non-silenced jobs owned by the user and
     * sends a notification to them if the job is alerting and they
     * haven't already had an email about it recently (as defined in
     * config/cronmon.php)
     */
    public function checkJobs()
    {
        if ($this->is_silenced) {
            return;
        }
        if (Storage::exists('cronmon.silenced')) {
            return;
        }
        $now = Carbon::now();
        foreach ($this->getNonSilencedJobs() as $job) {
            if ($job->isAlerting() and $job->hasntAlertedRecently()) {
                $this->jobemail = $job->getEmail();
                $this->notify(new JobHasGoneAwol($job));
                if ($job->shouldNotifyFallbackAddress()) {
                    Notification::route('mail', $job->fallback_email)
                        ->notify(new JobHasGoneAwol($job));
                }
                $job->updateLastAlerted();
            }
        }
    }

    /**
     * This tells laravel what email address(es) to send a notification to.
     * Used as jobs can have their alert sent to a different address than the
     * user who owns the job.
     */
    public function routeNotificationForMail()
    {
        if ($this->jobemail) {
            return $this->parseJobEmail();
        }
        return $this->email;
    }

    /**
     * A cronjob alert email can be sent to a comma-seperated list - this
     * just checks for that and returns an array of emails if it looks
     * like that's the case
     */
    public function parseJobEmail()
    {
        if (preg_match('/,/', $this->jobemail)) {
            return collect(preg_split('/\s*,\s*/', $this->jobemail))->each(function ($address, $key) {
                return trim($address);
            })->toArray();
        }
        return trim($this->jobemail);
    }

    public static function createNewAdmin($username, $email)
    {
        $user = static::create(
            ['username' => $username, 'email' => $email, 'is_admin' => true, 'password' => bcrypt(Str::random(32))]
        );
        $user->sendResetLink();
    }

    public static function register($properties)
    {
        $user = new static($properties);
        $user->password = bcrypt(Str::random(42));
        $user->save();
        $user->sendResetLink();
        return $user;
    }

    public function sendResetLink()
    {
        $token = $this->createResetToken();
        Log::info('Sent a password reset token for ' . $this->email . ' - token ' . $token);
        $this->sendPasswordResetNotification($token);
    }

    public function createResetToken()
    {
        return app('auth.password.broker')->createToken($this);
    }

    public function onTeam($team)
    {
        if (!$team) {
            return false;
        }
        if (!is_numeric($team)) {
            $team = $team->id;
        }
        return $this->teams()->pluck('team_id')->contains($team);
    }

    public function removeFromSystem()
    {
        foreach ($this->jobs as $job) {
            $job->pings()->delete();
        }
        $this->jobs()->delete();
        $this->teams()->detach();
        $this->delete();
    }
}
