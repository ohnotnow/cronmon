<?php
// @codingStandardsIgnoreFile

namespace Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Notifications\JobHasGoneAwol;
use App\Cronjob;
use App\User;
use App\Ping;
use Carbon\Carbon;

class CronjobTest extends TestCase
{
    /**
     * NOTE: this test doesn't really test anything as when Laravel is fake()ing the Notifications
     * it doesn't call the $user->routeNotificationForMail() method :-/  If you want to see that
     * it's working, comment out the Notification::fake() line and set your MAIL_DRIVER in .env
     * to be 'log' then check near the end of storage/logs/laravel.log.
     * The "fake" test is kept here in the hopes an update to the framework will resolve this...
     *
     * "Life, don't talk to me about life...."
     */
    public function test_a_job_with_comma_seperated_emails_goes_to_all_addresses()
    {
        Notification::fake();
        $user = factory(User::class)->create();
        $job = $this->createAwolJob($user, ['email' => 'a@example.com, b@example.com']);
        $user->checkJobs();
        Notification::assertSentTo([$user], JobHasGoneAwol::class);
    }

    public function test_it_can_create_a_valid_cronjob()
    {
        $user = factory(User::class)->create();
        $job = $this->createRunningJob($user);
        $this->assertEquals($user->jobs()->count(), 1);
        $firstJob = $user->jobs()->first();
        $this->assertEquals($job->id, $firstJob->id);
    }

    public function test_it_knows_if_it_has_gone_awol()
    {
        $user = factory(User::class)->create();
        $job = $this->createRunningJob($user);

        $result = $job->isAwol();

        $this->assertFalse($result);

        $job = $this->createAwolJob($user);
        $result = $job->isAwol();
        $this->assertTrue($result);
    }

    public function test_working_jobs_dont_send_notifications()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $this->createRunningJob($user);

        $user->checkJobs();

        Notification::assertNotSentTo([$user], JobHasGoneAwol::class);
    }

    public function test_awol_jobs_do_send_notifications()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $job = $this->createAwolJob($user);

        $user->checkJobs();

        Notification::assertSentTo(
            $user,
            JobHasGoneAwol::class,
            function ($notification, $channels) use ($job) {
                return $notification->job->id === $job->id;
            }
        );
    }

    public function test_silenced_awol_jobs_dont_send_notifications()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $this->createAwolJob($user, ['is_silenced' => true]);

        $user->checkJobs();

        Notification::assertNotSentTo([$user], JobHasGoneAwol::class);
    }

    public function test_silenced_user_has_no_notifications_sent()
    {
        Notification::fake();

        $user = factory(User::class)->create(['is_silenced' => true]);
        $this->createAwolJob($user);

        $user->checkJobs();

        Notification::assertNotSentTo([$user], JobHasGoneAwol::class);
    }

    public function test_silenced_env_variable_makes_no_notifications_sent()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $this->createAwolJob($user);

        \Storage::put('cronmon.silenced', '');
        $user->checkJobs();
        \Storage::delete('cronmon.silenced');

        Notification::assertNotSentTo([$user], JobHasGoneAwol::class);
    }

    public function test_jobs_that_have_been_awol_for_ages_start_notifying_a_fallback_address()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $job = $this->createAwolJob($user);
        $job->fallback_email = 'fallback@example.com';
        $job->save();
        // createAwolJob() uses 2hrs as it's error limit, so set the config to 1hr to use fallback
        config(['cronmon.fallback_delay' => '1']);

        $user->checkJobs();

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            JobHasGoneAwol::class,
            function ($notification, $channels, $notifiable) use ($job) {
                return $notifiable->routes['mail'] == 'fallback@example.com' && $notification->job->id === $job->id;
            }
        );
    }

    public function test_jobs_that_have_been_awol_for_a_short_time_dont_notify_a_fallback_address()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $job = $this->createAwolJob($user);
        $job->fallback_email = 'fallback@example.com';
        $job->save();
        // createAwolJob() uses 2hrs as it's error limit, so set the config to 3hr which should skip fallback
        config(['cronmon.fallback_delay' => '3']);

        $user->checkJobs();

        Notification::assertNotSentTo(
            new AnonymousNotifiable(),
            JobHasGoneAwol::class,
            function ($notification, $channels, $notifiable) use ($job) {
                return $notifiable->routes['mail'] == 'fallback@example.com';
            }
        );
    }
    public function test_pinging_a_job_creates_a_new_ping_record_when_the_job_is_logging_runs()
    {
        $user = factory(User::class)->create();
        $job = $this->createRunningJob($user);
        $job->is_logging = true;
        $job->save();
        $this->assertEquals(0, $job->pings()->count());
        $job->ping();
        $this->assertEquals(1, $job->pings()->count());
    }

    public function test_checking_an_awol_job_twice_in_a_short_space_of_time_only_triggers_one_alert()
    {
        Notification::fake();
        $user = factory(User::class)->create();
        $job = $this->createAwolJob($user);

        $user->checkJobs();
        Notification::assertSentTo([$user], JobHasGoneAwol::class);
        $this->assertEquals(1, Notification::sent($user, JobHasGoneAwol::class)->count());

        $user->checkJobs();
        $this->assertNotEquals(2, Notification::sent($user, JobHasGoneAwol::class)->count());
    }

    /**
     * This is here because sqlite doesn't enforce unsigned integers...
     * The HTML for creeating a job uses a value of -1 to indicate no team is selected, so
     * this has to be checked.  Funz.
     */
    public function test_creating_a_job_with_a_team_id_of_minus_one_is_converted_to_null()
    {
        $job = Cronjob::makeNew([
            'name' => 'QPQPQPQ',
            'team_id' => "-1",
            'period' => 1,
            'period_units' => 'hour',
            'grace' => 5,
            'grace_units' => 'day',
            'email' => 'whatever@example.com',
        ]);
        $this->assertNull($job->team_id);
    }

    public function test_pings_can_be_truncated()
    {
        $user = factory(User::class)->create();
        $job = $this->createAwolJob($user);
        $pings = factory(Ping::class, 200)->create(['cronjob_id' => $job->id]);
        $lastPing = $job->pings()->latest('created_at')->first();

        // check the count matches
        $this->assertEquals(200, $job->pings()->count());
        $job->truncatePings(100);
        $job->load('pings'); // refresh the db data
        $this->assertEquals(100, $job->pings()->count());

        // and double-check the last ping originally added to the table is still there
        // (just in case we are truncating the wrong rows)
        $this->assertEquals($lastPing->id, $job->pings()->latest('created_at')->first()->id);
    }

    public function test_pings_can_be_disabled()
    {
        $user = factory(User::class)->create();
        $job = $this->createAwolJob($user);
        $this->assertEquals(0, $job->pings()->count());
        $job->is_logging = false;
        $job->ping();
        $job = $job->fresh();
        $this->assertEquals(0, $job->pings()->count());
    }
}
