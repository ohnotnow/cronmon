<?php

namespace App\Http\Controllers;

use App\User;
use App\Template;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTemplate;
use App\Http\Requests\UpdateTemplate;

class TemplateController extends Controller
{
    public function index()
    {
        return view('template.index');
    }

    public function show(Template $template)
    {
        return view('template.show', [
            'template' => $template,
        ]);
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

    public function edit(Template $template)
    {
        $this->authorize('view', $template);

        return view('template.edit', [
            'template' => $template,
            'users' => User::orderBy('username')->get(),
        ]);
    }

    public function update(Template $template, UpdateTemplate $request)
    {
        $this->authorize('update', $template);

        $template->update($request->validated());
        $template->updateSlug();

        return redirect(route('template.index'));
    }
}
