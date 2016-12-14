<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $jobs = auth()->user()->getAvailableJobs();
        return view('home', compact('jobs'));
    }
}
