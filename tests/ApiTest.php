<?php

namespace Tests;

use App\Cronjob;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;

class ApiTest extends BrowserKitTest
{
    public function test_pinging_a_jobs_uri_updates_its_last_run_field()
    {
        $user = User::factory()->create();
        $job = $this->createRunningJob($user);

        $this->get('/ping/'.$job->uuid)->assertResponseOk();

        $jobCopy = $user->jobs()->first();
        $this->assertTrue($jobCopy->last_run->gt($job->last_run));
    }

    public function test_pinging_an_awol_jobs_uri_updates_its_status()
    {
        $user = User::factory()->create();
        $job = $this->createAwolJob($user);
        $this->assertTrue($job->isAwol());

        $this->get('/ping/'.$job->uuid)->assertResponseOk();

        $job = $job->fresh();
        $this->assertFalse($job->isAwol());
    }

    public function test_pinging_a_nonexistant_uri_fails()
    {
        $this->get('/ping/hellokitty')->assertResponseStatus(404);
    }
}
