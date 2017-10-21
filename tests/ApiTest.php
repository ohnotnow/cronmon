<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Cronjob;

class ApiTest extends Tests\BrowserKitTest
{
    public function test_pinging_a_jobs_uri_updates_its_last_run_field()
    {
        $user = factory(App\User::class)->create();
        $job = $this->createRunningJob($user);

        $this->get('/ping/' . $job->uuid)->assertResponseOk();

        $jobCopy = $user->jobs()->first();
        $this->assertTrue($jobCopy->last_run->gt($job->last_run));
    }

    public function test_pinging_an_awol_jobs_uri_updates_its_status()
    {
        $user = factory(App\User::class)->create();
        $job = $this->createAwolJob($user);
        $this->assertTrue($job->isAwol());

        $this->get('/ping/' . $job->uuid)->assertResponseOk();

        $job = $job->fresh();
        $this->assertFalse($job->isAwol());
    }

    public function test_pinging_a_nonexistant_uri_fails()
    {
        $this->get('/ping/hellokitty')->assertResponseStatus(404);
    }
}
