<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cron\CronExpression;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CronmonDiscover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronmon:discover {api_url} {api_key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update cronmon entries based on the Laravel schedular';

    /**
     * @var client
     */
    public $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        app()->make(\Illuminate\Contracts\Console\Kernel::class);
        $schedule = app()->make(\Illuminate\Console\Scheduling\Schedule::class);

        $responses = collect($schedule->events())->map(function($event) {
            $cron = CronExpression::factory($event->expression);
            $date = Carbon::now();
            if ($event->timezone) {
                $date->setTimezone($event->timezone);
            }
            return (object)[
                'expression' => $event->expression,
                'name' => config('app.name') . ' ' . str_after($event->command, '\'artisan\' '), 
            ];
        })->map(function ($event) {
            try {
                $response = $this->client->post(
                    $this->argument('api_url'), 
                    [
                        \GuzzleHttp\RequestOptions::JSON => [
                            'schedule' => $event->expression,
                            'name' => $event->name,
                            'api_key' => $this->argument('api_key')
                        ]
                    ]
                );
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $response = $e->getResponse();
                return '"' . $event->name . '" Failed : ' . $response->getReasonPhrase();
            }
            return '"' . $event->name . '" Success';
        });

        $responses->each(function ($response) {
            $this->line($response);
        });
    }
}
