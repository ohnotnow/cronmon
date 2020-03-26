<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTemplate;
use App\User;
use App\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        return view('template.index');
    }

    public function create()
    {
        return view('template.create', [
            'template' => new Template,
            'users' => User::orderBy('username')->get(),
        ]);
    }

    public function store(StoreTemplate $request)
    {
        $request->user()->addNewTemplate($request->validated());

        return redirect(route('template.index'));
    }
}
