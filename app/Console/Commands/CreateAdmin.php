<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronmon:createadmin {username} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new admin user. They will get a password reset notification.';

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
        $username = $this->argument('username');
        $email = $this->argument('email');
        $validator = Validator::make(['username' => $username, 'email' => $email], [
            'username' => 'required|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            throw new \RuntimeException('Aborting');
        }
        User::createNewAdmin($username, $email);
        $this->info('User created - password notification sent');
    }
}
