<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cronjob;

class ApiController extends Controller
{
    public function ping($uuid)
    {
        $job = Cronjob::findByUuid($uuid);
        if (!$job) {
            return response()->json(['errors' => 'Job not found', 'status' => 404], 404);
        }
        $job->ping(request('data', null));
    }
}
