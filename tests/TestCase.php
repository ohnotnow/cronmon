<?php

use Carbon\Carbon;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use DatabaseSetup;

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

    protected function setUp()
    {
        parent::setUp();
        $this->setupDatabase();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

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
