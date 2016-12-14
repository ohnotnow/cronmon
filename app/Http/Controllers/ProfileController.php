<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'username' => ['required', Rule::unique('users')->ignore($request->user()->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($request->user()->id)],
        ]);
        $request->user()->fill($request->all());
        $request->user()->save();
        return redirect()->route('profile.show');
    }
}
