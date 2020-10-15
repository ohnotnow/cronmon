<?php

// @codingStandardsIgnoreFile

namespace Tests\BrowserKit;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BrowserKitTeamTest extends \Tests\BrowserKitTest
{
    use DatabaseMigrations;

    public function test_a_user_can_associate_a_job_with_a_team()
    {
        $user = User::factory()->create();
        $team = Team::factory()->make();
        $job = $this->createRunningJob($user);
        $user->teams()->save($team);
        $this->actingAs($user)
            ->visit(route('job.show', $job->id))
            ->dontSee($team->name)
            ->visit(route('job.edit', $job->id))
            ->select($team->id, 'team_id')
            ->press('Update')
            ->visit(route('job.show', $job->id))
            ->see($team->name);
    }

    public function test_a_user_can_edit_a_job_which_is_allocated_to_one_of_their_teams()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $team = Team::factory()->create();
        $team->members()->attach([$user1->id, $user2->id]);
        $job = $this->createRunningJob($user1, ['team_id' => $team->id]);
        $this->actingAs($user2)
            ->visit(route('job.edit', $job->id))
            ->see('Edit job '.$job->name)
            ->type('QPQPQPQP', 'name')
            ->press('Update')
            ->see('Your jobs')
            ->see('QPQPQPQP');
    }

    public function test_a_user_can_add_and_remove_members_to_a_team()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $team = Team::factory()->create();
        $team->members()->attach([$user1->id, $user2->id]);

        $this->actingAs($user1)
            ->visit(route('team.show', $team->id))
            ->see($user1->username)
            ->see($user2->username)
            ->dontSee($user3->username)
            ->visit(route('teammember.edit', $team->id))
            ->check("remove[{$user2->id}]")
            ->select($user3->id, 'add')
            ->press('Update')
            ->see($user3->username)
            ->dontSee($user2->username);
    }

    public function test_a_user_cant_alter_a_team_they_are_not_on()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $team = Team::factory()->create();
        $team->members()->attach([$user1->id, $user2->id]);

        $this->actingAs($user3)
            ->get(route('team.show', $team->id))
            ->seeStatusCode(403);
        $this->actingAs($user3)
            ->get(route('team.edit', $team->id))
            ->seeStatusCode(403);
        $this->actingAs($user3)
            ->get(route('teammember.edit', $team->id))
            ->seeStatusCode(403);
    }

    public function test_an_admin_can_view_all_teams()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();

        $this->actingAs($admin)
            ->visit(route('job.index'))
            ->click('Teams')
            ->see('All teams')
            ->see($team1->name)
            ->see($team2->name);
    }
}
