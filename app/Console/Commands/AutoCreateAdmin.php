<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Validator;

class AutoCreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronmon:autocreateadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new admin user from either the ENV or "secret" files.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('cronmon.admin_email') || config('cronmon.admin_email_file')) {
            $this->createAdmin();
        } else {
            $this->info('No autocreated admin');
        }
    }

    protected function createAdmin()
    {
        $email = $this->findValueFor('email');
        $username = $this->findValueFor('username');
        $password = $this->findValueFor('password');

        $email = trim(strtolower($email));
        $admin = User::where('email', '=', $email)->first();
        if (! $admin) {
            $admin = User::createNewAdmin($username, $email, $password);
            $this->info('Auto-created new admin');

            return;
        }

        $validator = Validator::make(['email' => $email, 'password' => $password, 'username' => $username], [
            'email' => 'required|email|max:255|unique:users,email,'.$admin->id.',id',
            'password' => 'required|min:8',
            'username' => 'required|unique:users,email,'.$admin->id.',id',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            throw new \RuntimeException('Aborting');
        }

        $admin->update([
            'email' => $email,
            'is_admin' => true,
            'username' => $username,
            'password' => bcrypt($password),
        ]);

        $this->info('Auto-updated admin user');
    }

    public function findValueFor(string $key)
    {
        if (config("cronmon.admin_{$key}")) {
            return config("cronmon.admin_{$key}");
        }
        if (! config("cronmon.admin_{$key}_file")) {
            return null;
        }

        return file_get_contents(config("cronmon.admin_{$key}_file"));
    }
}
