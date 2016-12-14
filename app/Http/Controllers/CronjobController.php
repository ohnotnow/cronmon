<?php

namespace App\Http\Controllers;

use App\User;
use App\Cronjob;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCronjob;
use App\Http\Requests\UpdateCronjob;

class CronjobController extends Controller
{
    public function show($id)
    {
        $job = Cronjob::findOrFail($id);
        $this->authorize('edit-job', $job);
        return view('job.show', compact('job'));
    }

    public function index()
    {
        $jobs = Cronjob::orderBy('name')->with('user', 'team')->get();
        return view('job.admin.index', compact('jobs'));
    }

    public function create()
    {
        $job = Cronjob::newDefault();
        return view('job.create', compact('job'));
    }

    public function store(StoreCronjob $request)
    {
        $request->user()->addNewJob($request->all());
        return redirect()->route('home');
    }

    public function edit($id)
    {
        $job = Cronjob::findOrFail($id);
        $this->authorize('edit-job', $job);
        $users = User::orderBy('username')->get();
        return view('job.edit', compact('job', 'users'));
    }

    public function update(UpdateCronjob $request, $id)
    {
        $job = Cronjob::findOrFail($id);
        $this->authorize('edit-job', $job);
        $job->updateFromForm($request->all());
        return redirect()->route('home');
    }

    public function destroy($id)
    {
        $job = Cronjob::findOrFail($id);
        $this->authorize('edit-job', $job);
        $job->pings()->delete();
        $job->delete();
        return redirect()->route('home');
    }
}
