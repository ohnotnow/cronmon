<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AutoCreateAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function we_can_automatically_create_an_admin_user_automatically()
    {
        config(['cronmon.admin_username' => 'jenny']);
        config(['cronmon.admin_email' => 'jenny@example.com']);
        config(['cronmon.admin_password' => 'secret']);

        Artisan::call('cronmon:autocreateadmin');

        tap(User::first(), function ($user) {
            $this->assertEquals('jenny', $user->username);
            $this->assertEquals('jenny@example.com', $user->email);
            $this->assertTrue(Hash::check('secret', $user->password));
        });
    }

    /** @test */
    public function we_can_automatically_create_an_admin_user_automatically_via_the_content_of_files()
    {
        $usernameFile = tempnam(sys_get_temp_dir(), 'prefixx');
        file_put_contents($usernameFile, 'jackie');
        $emailFile = tempnam(sys_get_temp_dir(), 'prefixx');
        file_put_contents($emailFile, 'jackie@example.com');
        $passwordFile = tempnam(sys_get_temp_dir(), 'prefixx');
        file_put_contents($passwordFile, 'password1');

        config(['cronmon.admin_username_file' => $usernameFile]);
        config(['cronmon.admin_email_file' => $emailFile]);
        config(['cronmon.admin_password_file' => $passwordFile]);

        Artisan::call('cronmon:autocreateadmin');

        tap(User::first(), function ($user) {
            $this->assertEquals('jackie', $user->username);
            $this->assertEquals('jackie@example.com', $user->email);
            $this->assertTrue(Hash::check('password1', $user->password));
        });
    }
}
