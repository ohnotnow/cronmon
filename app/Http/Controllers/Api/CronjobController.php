<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Cronjob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\ValidCronExpression;

class CronjobController extends Controller
{
	public function update(Request $request)
	{
		$request->validate([
			'api_key' => 'required',
			'schedule' => ['required', new ValidCronExpression],
			'name' => 'required',
		]);

		$user = User::where('api_key', '=', $request->api_key)->firstOrFail();

		$job = Cronjob::where('name', '=', $request->name)->first();
		if (! $job) {
			$job = $user->addNewJob([
				'cron_schedule' => $request->schedule,
				'name' => $request->name,
				'team_id' => -1,
				'grace' => 1,
				'grace_units' => 'hour',
				'period' => 1,
				'period_units' => 'hour',
			]);
		} else {
			$job = $job->updateFromForm([
				'cron_schedule' => $request->schedule,
				'team_id' => $job->team_id,
			]);
		}

		return response()->json([
			'job' => $job->toArray(),
		]);
	}
}
