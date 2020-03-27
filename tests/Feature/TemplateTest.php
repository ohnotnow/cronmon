<?php

namespace Tests\Feature;

use App\Team;
use App\User;
use App\Cronjob;
use App\Template;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TemplateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_make_a_template_to_use_when_automatically_creating_a_job_via_an_api_call()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $team = factory(Team::class)->create();

        $response = $this->actingAs($user)->get(route('template.create'));

        $response->assertOk();

        $response = $this->actingAs($user)->post(route('template.store'), [
            'name' => 'My Amazing Template',
            'grace' => 5,
            'grace_units' => 'minute',
            'period' => 1,
            'period_units' => 'hour',
            'email' => 'jane@example.com',
            'is_silenced' => false,
            'team_id' => $team->id,
        ]);

        $response->assertRedirect(route('template.index'));
        tap(Template::first(), function ($template) use ($team, $user) {
            $this->assertEquals('My Amazing Template', $template->name);
            $this->assertEquals(5, $template->grace);
            $this->assertEquals('minute', $template->grace_units);
            $this->assertEquals(1, $template->period);
            $this->assertEquals('hour', $template->period_units);
            $this->assertEquals('jane@example.com', $template->email);
            $this->assertEquals($team->id, $template->team->id);
            $this->assertEquals(Str::slug($user->id . '-' . $template->name), $template->slug);
        });
    }

    /** @test */
    public function users_can_edit_their_own_templates()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $template = factory(Template::class)->create(['user_id' => $user->id]);
        $team = factory(Team::class)->create(['name' => 'blah']);

        $response = $this->actingAs($user)->get(route('template.edit', $template->id));

        $response->assertOk();

        $response = $this->actingAs($user)->post(route('template.update', $template->id), [
            'name' => 'My Amazing Template',
            'grace' => 5,
            'grace_units' => 'minute',
            'period' => 1,
            'period_units' => 'hour',
            'email' => 'jane@example.com',
            'team_id' => $team->id,
        ]);

        $response->assertRedirect(route('template.index'));
        tap($template->fresh(), function ($template) use ($team, $user) {
            $this->assertEquals('My Amazing Template', $template->name);
            $this->assertEquals(5, $template->grace);
            $this->assertEquals('minute', $template->grace_units);
            $this->assertEquals(1, $template->period);
            $this->assertEquals('hour', $template->period_units);
            $this->assertEquals('jane@example.com', $template->email);
            $this->assertEquals($team->id, $template->team->id);
            $this->assertEquals(Str::slug($user->id . '-' . $template->name), $template->slug);
        });
    }

    /** @test */
    public function users_cant_edit_other_peoples_templates()
    {
        $user = factory(User::class)->create();
        $template = factory(Template::class)->create();
        $team = factory(Team::class)->create(['name' => 'blah']);

        $response = $this->actingAs($user)->get(route('template.edit', $template->id));

        $response->assertForbidden();

        $response = $this->actingAs($user)->post(route('template.update', $template->id), [
            'name' => 'My Amazing Template',
            'grace' => 5,
            'grace_units' => 'minute',
            'period' => 1,
            'period_units' => 'hour',
            'email' => 'jane@example.com',
            'team_id' => $team->id,
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function we_can_create_a_new_job_based_on_a_template()
    {
        $template = factory(Template::class)->create();

        $response = $this->postJson(route('api.template.create_job', $template->slug));

        $response->assertOk();
        $response->assertJson([
            'data' => Cronjob::first()->toArray(),
        ]);
    }
}
