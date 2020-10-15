<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronmon:checkjobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for any awol jobs and sends alerts';

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
        User::all()->each(function ($user, $key) {
            $user->checkJobs();
        });
    }
}
