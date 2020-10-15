<?php

namespace App\Console\Commands;

use App\Models\Cronjob;
use Illuminate\Console\Command;

class TruncatePings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronmon:truncatepings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncates the "pings" (logs for jobs)';

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
        $jobs = Cronjob::all();
        foreach ($jobs as $job) {
            $job->truncatePings(config('cronmon.keep_pings', 100));
        }
    }
}
