@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        My details
    	<a class="button is-pulled-right" href="{{{ route('profile.edit') }}}">Edit</a>
    </h2>
    @include('user.partials.profile')
    <hr />
    <h4 class="title is-4">
        My Teams
        <a class="button is-pulled-right" href="{{{ route('team.create') }}}">Add new team</a>
    </h4>
    @include('team.partials.index', ['teams' => $user->teams])
    <hr />
    <h4 class="title is-4">My Jobs</h4>
    @include('job.partials.index', ['jobs' => $user->getAvailableJobs()])
@endsection
