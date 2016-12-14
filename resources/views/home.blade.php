@extends('layouts.app')

@section('content')
    <div class="tabs">
      <ul>
        <li class="is-active" id="yourjobstab">
            <a id="yourjobs"><h3 class="title is-3">
                Your jobs
            </h3></a>
        </li>
        <li id="teamjobstab">
            <a id="teamjobs"><h3 class="title is-3">Team jobs</h3></a>
        </li>
       </ul>
       <ul class="is-right">
        <li>
            <a class="button is-pulled-right" href="{{ route('job.create') }}">
                Add new job
            </a>
        <li>
      </ul>
    </div>
    <div id="yourjobstable">
        @include('job.partials.index')
    </div>
    @if (Auth::user()->getTeamJobs()->count() > 0)
        <div id="teamjobstable" style="display:none">
            @include('job.partials.index', ['jobs' => Auth::user()->getTeamJobs()])
        </div>
    @endif
@endsection
