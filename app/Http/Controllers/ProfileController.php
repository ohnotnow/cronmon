<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
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
        $data = $this->validate($request, [
            'username' => ['required', Rule::unique('users')->ignore($request->user()->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($request->user()->id)],
        ]);
        $request->user()->fill($data);
        if ($request->filled('new_api_key')) {
            $key = $request->user()->generateNewApiKey();
            session()->flash('success', $key);
        }
        $request->user()->save();

        return redirect()->route('profile.show');
    }
}
