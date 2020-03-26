@extends('layouts.app')

@section('content')
    <div class="tabs">
      <ul>
        <li class="is-active" id="yourtemplatestab">
            <a id="yourtemplates"><h3 class="title is-3">
                Your templates
            </h3></a>
        </li>
        <li id="teamtemplatestab">
            <a id="teamtemplates"><h3 class="title is-3">Team templates</h3></a>
        </li>
       </ul>
       <ul class="is-right">
        <li>
            <a class="button is-pulled-right" href="{{ route('job.create') }}">
                Add new template
            </a>
        <li>
      </ul>
    </div>
    <div id="yourjobstable">
        @include('template.partials.index')
    </div>
    @if (Auth::user()->getTeamTemplates()->count() > 0)
        <div id="teamtemplatetable" style="display:none">
            @include('template.partials.index', ['templates' => Auth::user()->getTeamTemplates()])
        </div>
    @endif
@endsection
