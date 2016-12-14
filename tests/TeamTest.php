<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Team;

class TeamTest extends TestCase
{

    public function test_we_can_check_a_user_is_on_a_given_team()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->make();

        $this->assertFalse($user->onTeam($team));

        $user->teams()->save($team);

        $this->assertTrue($user->onTeam($team));
    }

    public function test_a_user_can_create_a_team()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->visit(route('profile.show'))
            ->see('My Teams')
            ->dontSee('NEWTEAM')
            ->click('Add new team')
            ->type('NEWTEAM', 'name')
            ->press('Create')
            ->see('Team details')
            ->see('NEWTEAM')
            ->see($user->username);
        $user = $user->fresh();
        $this->actingAs($user)
            ->visit(route('profile.show'))
            ->see('NEWTEAM');
    }

    public function test_a_user_can_edit_a_team()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->make();
        $user->teams()->save($team);
        $this->actingAs($user)
            ->visit(route('profile.show'))
            ->see($team->name)
            ->click($team->name)
            ->click('Edit')
            ->type('NEWNAME', 'name')
            ->press('Update')
            ->see('Team details')
            ->see('NEWNAME');
    }

    public function test_a_user_can_associate_a_job_with_a_team()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->make();
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
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $team = factory(Team::class)->make();
        $user1->teams()->save($team);
        $user2->teams()->save($team);
        $job = $this->createRunningJob($user1, ['team_id' => $team->id]);
        $this->actingAs($user2)
            ->visit(route('job.edit', $job->id))
            ->see('Edit job ' . $job->name)
            ->type('QPQPQPQP', 'name')
            ->press('Update')
            ->see('Your jobs')
            ->see('QPQPQPQP');
    }

    public function test_a_user_can_add_and_remove_members_to_a_team()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();
        $team = factory(Team::class)->create();
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
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();
        $team = factory(Team::class)->create();
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

    public function test_a_user_cant_be_added_to_a_team_twice()
    {
        $user1 = factory(User::class)->create();
        $team = factory(Team::class)->create();

        $team->addMember($user1->id);
        $this->assertEquals(1, $team->members->count());

        $team->addMember($user1->id);
        $team->load('members');     // force laravel to reload the members relation fresh
        $this->assertEquals(1, $team->members->count());
    }

    public function test_an_admin_can_view_all_teams()
    {
        $admin = factory(User::class)->create(['is_admin' => true]);
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();

        $this->actingAs($admin)
            ->visit(route('job.index'))
            ->click('Teams')
            ->see('All teams')
            ->see($team1->name)
            ->see($team2->name);
    }
}
