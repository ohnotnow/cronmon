<?php

namespace Tests\Feature;

use App\User;
use App\Cronjob;
use App\Team;
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
        $user = User::factory()->create(['api_key' => 'hello']);

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
        $user = User::factory()->create(['api_key' => 'hello']);
        $job = Cronjob::factory()->create(['name' => 'fred', 'cron_schedule' => '*/15 * * * *', 'user_id' => $user->id]);

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
    public function the_api_call_can_optionally_include_the_grace_team_and_period()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create(['api_key' => 'hello']);
        $team = Team::factory()->create(['name' => 'my team']);
        $team->addMember($user->id);

        $response = $this->postJson(route('api.cronjob.update'), [
            'api_key' => 'hello',
            'schedule' => '* * * * *',
            'name' => 'my amazing task',
            'team' => 'my team',
            'grace' => 5,
            'grace_units' => 'minute',
            'period' => 3,
            'period_units' => 'hour',
        ]);

        $response->assertJsonMissingValidationErrors();
        $response->assertOk();
        $job = Cronjob::first();
        $this->assertTrue($job->user->is($user));
        $this->assertEquals('my amazing task', $job->name);
        $this->assertEquals('* * * * *', $job->cron_schedule);
        $this->assertTrue($job->team->is($team));
        $this->assertEquals(5, $job->grace);
        $this->assertEquals('minute', $job->grace_units);
        $this->assertEquals(3, $job->period);
        $this->assertEquals('hour', $job->period_units);
    }

    /** @test */
    public function we_must_pass_either_a_schedule_or_period()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create(['api_key' => 'hello']);

        $response = $this->postJson(route('api.cronjob.update'), [
                'api_key' => 'hello',
                'schedule' => '',
                'name' => 'my amazing task',
                'period' => '',
                'period_units' => '',
            ]);

        $response->assertJsonValidationErrors(['schedule', 'period', 'period_units']);

        $response = $this->postJson(route('api.cronjob.update'), [
                'api_key' => 'hello',
                'schedule' => '',
                'name' => 'my amazing task',
                'period' => '1',
                'period_units' => 'hour',
            ]);

        $response->assertJsonMissingValidationErrors();

        $response = $this->postJson(route('api.cronjob.update'), [
                'api_key' => 'hello',
                'schedule' => '1 * * * *',
                'name' => 'my amazing task',
                'period' => '',
                'period_units' => '',
            ]);

        $response->assertJsonMissingValidationErrors();
    }

    /** @test */
    public function data_passed_in_the_request_must_be_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create(['api_key' => 'hello']);
        $team = Team::factory()->create(['name' => 'my team']);
        $job = Cronjob::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson(route('api.cronjob.update'), [
            'api_key' => 'hello',
            'schedule' => 'whatever',
            'name' => 'my amazing task',
            'team' => 'other team',
            'grace' => 'hi',
            'grace_units' => 'some time',
            'period' => 'victorian',
            'period_units' => 'millimeters',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['schedule', 'team', 'grace', 'grace_units', 'period', 'period_units']);
    }

    /** @test */
    public function we_cannot_add_a_job_to_a_team_we_are_not_part_of()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create(['api_key' => 'hello']);
        $job = Cronjob::factory()->create(['user_id' => $user->id]);
        $team = Team::factory()->create(['name' => 'the A team']);

        $response = $this->postJson(route('api.cronjob.update'), [
            'api_key' => 'hello',
            'schedule' => '* * * * *',
            'name' => 'my amazing task',
            'team' => 'the A team',
        ]);

        $response->assertStatus(404);
        $job = Cronjob::first();
        $this->assertNull($job->team_id);
    }

    /** @test */
    public function there_is_an_artisan_command_to_populate_the_cronjob_entries()
    {
        $client = $this->guzzler->getClient();
        $this->app->instance(\GuzzleHttp\Client::class, $client);
        $this->guzzler->queueResponse(new \GuzzleHttp\Psr7\Response(200, [], "{job: {}}"));
        $this->guzzler->queueResponse(new \GuzzleHttp\Psr7\Response(200, [], "{job: {}}"));
        $user = User::factory()->create(['api_key' => 'hello']);

        $this->artisan('cronmon:discover http://example.com/ hello')
            ->expectsOutput('"Cronmon cronmon:checkjobs" Success')
            ->expectsOutput('"Cronmon cronmon:truncatepings" Success');
    }

    /** @test */
    public function we_can_get_the_details_of_a_job_via_http()
    {
        $user = User::factory()->create();
        $job = Cronjob::factory()->create();

        $response = $this->getJson(route('api.cronjob.show', $job->uuid));

        $response->assertOk();
        $response->assertJson([
            'data' => $job->toArray(),
        ]);
    }
}
