<?php

namespace Tests\Feature;

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
            'team_id' => null,
        ]);

        $response->assertRedirect(route('template.index'));
        $response->assertSee('My Amazing Template');
        $response->asssertSee(Str::snake('My Amazing Template'));
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
