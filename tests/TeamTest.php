<?php
// @codingStandardsIgnoreFile

namespace Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Team;
use App\Cronjob;

class TeamTest extends TestCase
{
    public function test_we_can_check_a_user_is_on_a_given_team()
    {
        $user = User::factory()->create();
        $team = Team::factory()->make();

        $this->assertFalse($user->onTeam($team));

        $user->teams()->save($team);

        $this->assertTrue($user->onTeam($team));
    }

    public function test_a_user_cant_be_added_to_a_team_twice()
    {
        $user1 = User::factory()->create();
        $team = Team::factory()->create();

        $team->addMember($user1->id);
        $this->assertEquals(1, $team->members->count());

        $team->addMember($user1->id);
        $team->load('members');     // force laravel to reload the members relation fresh
        $this->assertEquals(1, $team->members->count());
    }

    /** @test */
    public function deleting_a_team_removes_all_members()
    {
        $user1 = User::factory()->create();
        $team = Team::factory()->create();

        $team->addMember($user1->id);

        $this->assertCount(1, $user1->teams);

        $team->delete();

        $this->assertCount(0, $user1->fresh()->teams);
    }

    /** @test */
    public function deleting_a_team_sets_any_jobs_which_were_associated_with_it_to_have_a_null_team_id()
    {
        $user1 = User::factory()->create();
        $team = Team::factory()->create();
        $job = Cronjob::factory()->create([
            'user_id' => $user1->id,
            'team_id' => $team->id,
        ]);

        $team->delete();

        $this->assertNull($job->fresh()->team_id);
    }

    /** @test */
    public function a_member_of_a_team_can_delete_the_team()
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $team->addMember($user);

        $response = $this->actingAs($user)->delete(route('team.delete', $team->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }

    /** @test */
    public function a_user_cant_delete_a_team_they_are_not_a_member_of()
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('team.delete', $team->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('teams', ['id' => $team->id]);
    }

    /** @test */
    public function an_admin_can_delete_any_team()
    {
        $team = Team::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->delete(route('team.delete', $team->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }
}
