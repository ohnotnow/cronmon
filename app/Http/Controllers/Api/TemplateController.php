<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function store($slug, Request $request)
    {
        $template = Template::where('slug', '=', $slug)->firstOrFail();

        $job = $template->createNewJob();

        return response()->json([
            'data' => $job->toArray(),
        ]);
    }
}
