<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('name')->with('members', 'jobs')->get();

        return view('team.index', compact('teams'));
    }

    public function create()
    {
        $team = new Team;

        return view('team.create', compact('team'));
    }

    public function store(Request $request)
    {
        $team = new Team($request->only('name'));
        $request->user()->teams()->save($team);

        return redirect()->route('team.show', $team->id);
    }

    public function show(Team $team)
    {
        $this->authorize('edit-team', $team);

        return view('team.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $this->authorize('edit-team', $team);

        return view('team.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $this->authorize('edit-team', $team);
        $team->fill($request->only('name'));
        $team->save();

        return redirect()->route('team.show', $team->id);
    }

    public function destroy(Team $team)
    {
        $this->authorize('edit-team', $team);

        $team->delete();

        return redirect()->route('home');
    }
}
