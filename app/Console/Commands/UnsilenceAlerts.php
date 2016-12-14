<?php

namespace App\Console\Commands;

use Storage;
use Illuminate\Console\Command;

class UnsilenceAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronmon:unsilence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unsilence alerts';

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
        Storage::delete('cronmon.silenced');
    }
}
