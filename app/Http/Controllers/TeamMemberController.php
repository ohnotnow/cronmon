<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function edit($id)
    {
        $team = Team::findOrFail($id);
        $this->authorize('edit-team', $team);
        $users = User::orderBy('username')->get();

        return view('team.member.edit', compact('team', 'users'));
    }

    public function update($id, Request $request)
    {
        $team = Team::findOrFail($id);
        $this->authorize('edit-team', $team);
        if ($request->filled('remove')) {
            $team->removeMembers($request->remove);
        }
        if ($request->filled('add')) {
            $team->addMember($request->add);
        }

        return redirect()->route('team.show', $team->id);
    }
}
