<?php

// @codingStandardsIgnoreFile

namespace Tests\BrowserKit;

use App\Models\Cronjob;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Notification;

class UiTest extends \Tests\BrowserKitTest
{
    public function test_a_user_can_see_their_jobs()
    {
        $user = User::factory()->create();
        $job1 = $this->createRunningJob($user);
        $job2 = $this->createRunningJob($user);
        $this->actingAs($user)
            ->visit(route('job.index'))
            ->see('Your jobs')
            ->see($job1->name)
            ->see($job1->getLastRun())
            ->see($job2->name)
            ->see($job2->getLastRun());
    }

    public function test_a_user_can_create_a_valid_job()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->visit(route('job.index'))
            ->dontSee('TESTJOB')
            ->click('Add new job')
            ->see('Add new job')
            ->type('TESTJOB', 'name')
            ->type('1', 'period')
            ->select('day', 'period_units')
            ->type('30', 'grace')
            ->select('minute', 'grace_units')
            ->type('fallback@example.com', 'fallback_email')
            ->press('Create new job')
            ->see('Your jobs')
            ->see('TESTJOB');
        $job = Cronjob::first();
        $this->assertEquals('TESTJOB', $job->name);
        $this->assertEquals('fallback@example.com', $job->fallback_email);
    }

    public function test_a_user_can_edit_an_existing_job()
    {
        $user = User::factory()->create();
        $job = $this->createRunningJob($user);
        $this->actingAs($user)
            ->visit(route('job.index'))
            ->see($job->name)
            ->click($job->name)
            ->click('Edit job')
            ->see('Edit job '.$job->name)
            ->type('ALLOFYOURCRONSAREBELONGTOUS', 'name')
            ->type($job->period + 5, 'period')
            ->check('is_silenced')
            ->press('Update')
            ->see('Your jobs')
            ->dontSee($job->name)
            ->see('ALLOFYOURCRONSAREBELONGTOUS')
            ->see('Every '.($job->period + 5));
    }

    public function test_an_admin_can_add_a_new_user()
    {
        Notification::fake();
        $user = User::factory()->create(['is_admin' => true]);
        $this->actingAs($user)
            ->visit(route('job.index'))
            ->click('Users')
            ->see('Current users')
            ->see($user->username)
            ->dontSee('HELLOKITTY')
            ->click('Add user')
            ->see('Add new user')
            ->type('HELLOKITTY', 'username')
            ->type('test@test.com', 'email')
            ->press('Create new user')
            ->see('Current users')
            ->see('HELLOKITTY')
            ->see('test@test.com');
        $newUser = User::where('username', 'HELLOKITTY')->first();
        Notification::assertSentTo([$newUser], ResetPasswordNotification::class);
    }

    public function test_a_user_can_view_a_job()
    {
        $user = User::factory()->create();
        $job = $this->createRunningJob($user);
        $this->actingAs($user)
            ->visit(route('job.index'))
            ->click($job->name)
            ->see($job->name)
            ->see($job->getSchedule());
    }

    public function test_a_user_can_delete_a_job()
    {
        $user = User::factory()->create();
        $job = $this->createRunningJob($user);
        $this->actingAs($user)
            ->visit(route('job.index'))
            ->see($job->name)
            ->click($job->name)
            ->click('Edit job')
            ->press('Delete job')
            ->see('Your jobs')
            ->dontSee($job->name);
    }

    public function test_admin_can_see_all_jobs()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $job = $this->createRunningJob($user);
        $this->actingAs($admin)
            ->visit(route('job.index'))
            ->click('Jobs')
            ->see('All jobs')
            ->see($job->name);
    }

    public function test_an_admin_can_delete_another_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $job = $this->createRunningJob($user);
        $this->actingAs($admin)
            ->visit(route('user.index'))
            ->see('Current users')
            ->see($user->username)
            ->click($user->username)
            ->click('Edit user')
            ->press('Delete user')
            ->see('Current users')
            ->dontSee($user->username);
    }

    public function test_a_user_can_edit_their_account()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->visit(route('home'))
            ->click('My account')
            ->see('My details')
            ->click('Edit')
            ->see('Edit my details')
            ->type('FOFOFOFOF', 'username')
            ->type('yourehavingalaugh@example.com', 'email')
            ->press('Update')
            ->see('My details')
            ->see('FOFOFOFOF')
            ->see('yourehavingalaugh');
    }

    public function test_a_user_can_silence_their_account()
    {
        $user = User::factory()->create(['is_silenced' => false]);
        $this->actingAs($user)
            ->visit(route('profile.edit'))
            ->see('Edit my details')
            ->check('is_silenced')
            ->press('Update')
            ->see('My details')
            ->see('
            <h4 class="title is-4">Silenced Alarms?</h4>
            <p class="subtitle">
                Yes
            </p>');
    }

    public function test_a_user_can_generate_an_api_key()
    {
        $user = User::factory()->create(['api_key' => null]);
        $this->actingAs($user)
            ->visit(route('profile.edit'))
            ->see('Edit my details')
            ->check('new_api_key')
            ->press('Update')
            ->see('My details');
        $this->assertNotNull($user->fresh()->api_key);
    }

    public function test_a_user_can_login_with_email_or_password()
    {
        $password = 'hellokitty';
        $username = 'testuser';
        $email = 'test@example.com';
        $user = User::factory()->create(
            ['username' => $username, 'email' => $email, 'password' => bcrypt($password)]
        );
        $this->visit(route('login'))
            ->type($username, 'login')
            ->type($password, 'password')
            ->press('Log in')
            ->see('Your jobs')
            ->press('Log out');
        $this->visit(route('login'))
            ->type($email, 'login')
            ->type($password, 'password')
            ->press('Log in')
            ->see('Your jobs')
            ->press('Log out');
    }

    public function test_user_cannot_create_two_jobs_with_the_same_name()
    {
        $user = User::factory()->create();
        $job1 = $this->createRunningJob($user);
        $job2 = $this->createRunningJob($user);

        $this->actingAs($user)
            ->visit(route('job.edit', $job2->id))
            ->type($job1->name, 'name')
            ->press('Update')
            ->see('The name has already been taken');
        $this->actingAs($user)
            ->visit(route('job.create'))
            ->type($job1->name, 'name')
            ->press('Create new job')
            ->see('The name has already been taken');
    }

    public function test_different_users_can_create_jobs_with_the_same_name_as_another_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $job1 = $this->createRunningJob($user1);

        $this->actingAs($user2)
            ->visit(route('job.create'))
            ->type($job1->name, 'name')
            ->press('Create new job')
            ->dontSee('The name has already been taken')
            ->see($job1->name);
    }

    public function test_user_can_regenerate_a_uuid()
    {
        $user = User::factory()->create();
        $job = $this->createRunningJob($user, ['is_silenced' => true]);

        $this->actingAs($user)
            ->visit(route('job.edit', $job->id))
            ->see('is_silenced')
            ->check('regenerate_uuid')
            ->press('Update')
            ->dontSee($job->uuid);
        $updatedJob = $job->fresh();
        $this->assertNotEquals($job->uuid, $updatedJob->uuid);
    }

    public function test_an_admin_can_change_job_owner()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $job = $this->createRunningJob($user1);
        $this->actingAs($admin)
            ->visit(route('user.show', $user1->id))
            ->see($job->name)
            ->visit(route('job.edit', $job->id))
            ->select($user2->id, 'user_id')
            ->check('is_silenced')
            ->press('Update')
            ->visit(route('user.show', $user1->id))
            ->dontSee($job->name)
            ->visit(route('user.show', $user2->id))
            ->see($job->name);
    }

    public function test_an_admin_can_generate_a_password_reset_for_a_user()
    {
        Notification::fake();
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $this->actingAs($user)
            ->visit(route('profile.edit'))
            ->dontSee('Reset users password');
        $this->actingAs($admin)
            ->visit(route('user.edit', $user->id))
            ->see('Reset users password')
            ->check('reset_password')
            ->press('Update');
        Notification::assertSentTo([$user], ResetPasswordNotification::class);
    }

    public function test_a_job_which_is_set_to_log_runs_shows_its_ping_history()
    {
        $user = User::factory()->create();
        $job = $this->createRunningJob($user);
        $job->is_logging = true;
        $job->save();
        $job->ping('somedatawhatIposted');
        $ping = $job->pings()->first();
        $this->actingAs($user)
            ->visit(route('job.show', $job->id))
            ->see('Run history')
            ->see('somedatawhatIposted')
            ->see($ping->created_at->format('d/m/Y H:i'));
    }

    public function test_a_regular_user_cannot_view_or_edit_another_users_job()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $job = $this->createRunningJob($user2);
        $this->actingAs($user1)
            ->get(route('job.show', $job->id))
            ->seeStatusCode(403);
        $this->actingAs($user1)
            ->get(route('job.edit', $job->id))
            ->seeStatusCode(403);
        $this->actingAs($user1)
            ->post(route('job.update', $job->id))
            ->seeStatusCode(403);
    }

    public function test_a_regular_user_cannot_access_admin_routes()
    {
        $user = User::factory()->create();
        $job = $this->createRunningJob($user);
        $this->actingAs($user)
            ->get(route('user.show', $user->id))
            ->seeStatusCode(302);
        $this->actingAs($user)
            ->get(route('job.index'))
            ->seeStatusCode(302);
    }

    public function test_a_user_can_add_notes_to_a_cronjob()
    {
        $user = User::factory()->create();
        $job = $this->createRunningJob($user);
        $this->actingAs($user)
            ->visit(route('job.show', $job->id))
            ->see('Notes')
            ->dontSee('WPWPWPWPWPWP')
            ->visit(route('job.edit', $job->id))
            ->type('WPWPWPWPWPWP', 'notes')
            ->press('Update')
            ->visit(route('job.show', $job->id))
            ->see('Notes')
            ->see('WPWPWPWPWPWP');
    }
}
