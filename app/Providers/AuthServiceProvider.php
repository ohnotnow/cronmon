<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->is_admin) {
                return true;
            }
        });

        Gate::define('update-job', function ($user, $job) {
            if ($user->id == $job->user_id) {
                return true;
            }
            if ($user->onTeam($job->team_id)) {
                return true;
            }

            return false;
        });
        Gate::define('view-job', function ($user, $job) {
            if ($user->id == $job->user_id) {
                return true;
            }
            if ($user->onTeam($job->team_id)) {
                return true;
            }

            return false;
        });
        Gate::define('edit-job', function ($user, $job) {
            if ($user->id == $job->user_id) {
                return true;
            }
            if ($user->onTeam($job->team_id)) {
                return true;
            }

            return false;
        });
        Gate::define('edit-team', function ($user, $team) {
            if ($user->onTeam($team)) {
                return true;
            }

            return false;
        });
    }
}
