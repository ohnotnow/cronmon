<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $guarded = [];

    public $units = [
        'minute' => 'Minutes',
        'hour' => 'Hours',
        'day' => 'Days',
        'week' => 'Weeks'
    ];

    public static function makeNew($data)
    {
        if (array_key_exists('team_id', $data)) {
            if ($data['team_id'] == -1) {
                $data['team_id'] = null;
            }
        }
        $job = new static($data);
        $job->uuid = CronUuid::generate();
        $job->slug = $job->user_id . '-' . Str::slug($job->name);
        return $job;
    }
}
