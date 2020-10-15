<?php

namespace App\Http\Controllers;

use App\Models\Cronjob;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function ping($uuid)
    {
        $job = Cronjob::findByUuid($uuid);
        if (! $job) {
            return response()->json(['errors' => 'Job not found', 'status' => 404], 404);
        }
        $job->ping(request('data', null));
    }
}
