<?php
// @codingStandardsIgnoreFile

namespace Tests;

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

}
