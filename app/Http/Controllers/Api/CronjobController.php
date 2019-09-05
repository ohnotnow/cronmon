<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Cronjob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\ValidCronExpression;
use App\Team;

class CronjobController extends Controller
{
	public function update(Request $request)
	{
		$request->validate([
			'api_key' => 'required',
			'schedule' => ['required_without_all:period,period_units', new ValidCronExpression],
			'name' => 'required',
			'team' => 'nullable|string|exists:teams,name',
			'grace' => 'nullable|numeric',
			'grace_units' => 'nullable|in:minute,hour,day,week',
			'period' => 'required_without:schedule|numeric',
			'period_units' => 'required_without:schedule|in:minute,hour,day,week',
		]);

		$user = User::where('api_key', '=', $request->api_key)->firstOrFail();
		$team = false;
		if ($request->filled('team')) {
			$team = $user->teams()->where('name', '=', $request->team)->firstOrFail();
		}

		$job = Cronjob::where('name', '=', $request->name)->first();
		if (! $job) {
			$job = $user->addNewJob([
				'cron_schedule' => $request->schedule,
				'name' => $request->name,
				'team_id' => $team ? $team->id : -1,
				'grace' => $request->grace ?? 1,
				'grace_units' => $request->grace_units ?? 'hour',
				'period' => $request->period ?? 1,
				'period_units' => $request->period_units ?? 'hour',
			]);
		} else {
			$job = $job->updateFromForm([
				'cron_schedule' => $request->schedule,
				'team_id' => $team ? $team->id : $job->team_id,
				'grace' => $request->grace ?? 1,
				'grace_units' => $request->grace_units ?? 'hour',
				'period' => $request->period ?? 1,
				'period_units' => $request->period_units ?? 'hour',
			]);
		}

		return response()->json([
			'job' => $job->toArray(),
		]);
	}
}
