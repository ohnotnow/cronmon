<?php

namespace Tests\Feature;

use App\User;
use App\Cronjob;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use BlastCloud\Guzzler\UsesGuzzler;

class CronjobApiCrudTest extends TestCase
{
    use RefreshDatabase;
    use UsesGuzzler;

    /** @test */
    public function we_can_create_a_new_cronjob_entry_via_an_api_call()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create(['api_key' => 'hello']);

        $response = $this->postJson(route('api.cronjob.update'), [
            'api_key' => 'hello',
            'schedule' => '* * * * *',
            'name' => 'my amazing task',
        ]);

        $response->assertOk();
        $job = Cronjob::first();
        $this->assertTrue($job->user->is($user));
        $this->assertEquals('my amazing task', $job->name);
        $this->assertEquals('* * * * *', $job->cron_schedule);
    }

    /** @test */
    public function we_can_update_an_existing_cronjob_entry_via_an_api_call()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create(['api_key' => 'hello']);
        $job = factory(Cronjob::class)->create(['name' => 'fred', 'cron_schedule' => '*/15 * * * *', 'user_id' => $user->id]);

        $response = $this->postJson(route('api.cronjob.update'), [
            'api_key' => 'hello',
            'schedule' => '* * * * *',
            'name' => 'fred',
        ]);

        $response->assertOk();
        $job = Cronjob::first();
        $this->assertTrue($job->user->is($user));
        $this->assertEquals('fred', $job->name);
        $this->assertEquals('* * * * *', $job->cron_schedule);
    }

    /** @test */
    public function there_is_an_artisan_command_to_populate_the_cronjob_entries()
    {
        $client = $this->guzzler->getClient();
        $this->app->instance(\GuzzleHttp\Client::class, $client);
        $this->guzzler->queueResponse(new \GuzzleHttp\Psr7\Response(200, [], "{job: {}}"));
        $this->guzzler->queueResponse(new \GuzzleHttp\Psr7\Response(200, [], "{job: {}}"));
        $user = factory(User::class)->create(['api_key' => 'hello']);

        $this->artisan('cronmon:discover http://example.com/ hello')
            ->expectsOutput('"Cronmon cronmon:checkjobs" Success')
            ->expectsOutput('"Cronmon cronmon:truncatepings" Success');
    }
}
