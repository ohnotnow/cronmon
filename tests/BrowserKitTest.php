<?php

namespace Tests;

use Carbon\Carbon;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class BrowserKitTest extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    public $jobData = [
            'name' => 'hellothere',
            'grace' => 5,
            'grace_units' => 'minute',
            'period' => 1,
            'period_units' => 'hour',
            'email' => '',
            'is_silenced' => false,
            'team_id' => null,
    ];

    public function createAwolJob($user, $data = [])
    {
        $data = array_merge($this->jobData, $data);
        $job = $user->addNewJob($data);
        $job->last_run = Carbon::now()->subHours(2);
        $job->last_alerted = Carbon::now()->subHours(2);
        $job->save();
        return $job;
    }

    public function createRunningJob($user, $data = [])
    {
        $data = array_merge($this->jobData, $data);
        $job = $user->addNewJob($data);
        $job->last_run = Carbon::now()->subMinutes(2);
        $job->save();
        return $job;
    }
}
