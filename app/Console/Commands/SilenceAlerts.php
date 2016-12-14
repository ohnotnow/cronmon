<?php

namespace App\Console\Commands;

use Storage;
use Illuminate\Console\Command;

class SilenceAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronmon:silence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Silence all alerts';

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
        Storage::put('cronmon.silenced', '');
    }
}
