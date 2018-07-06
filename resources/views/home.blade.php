@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Overview</span>
            <span class="flex-1 text-right">
                <a class="button text-base" href="{{ route('job.create') }}">Add job</a>
            </span>
        </h4>
    </div>
    <job-tabs
      :userjobs='@json(Auth::user()->getAvailableJobs())'
      :teamjobs='@json(Auth::user()->getTeamJobs())'
    >
    </job-tabs>
</div>
@endsection
