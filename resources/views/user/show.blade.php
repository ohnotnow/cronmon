@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        User details for {{ $user->username }}
    	<a class="button is-pulled-right" href="{{{ route('user.edit', $user->id) }}}">Edit user</a>
    </h2>
    @include('user.partials.profile')
    <hr />
    <h4 class="title is-4">Jobs</h4>
    @include('job.partials.index', ['jobs' => $user->getAvailableJobs()])
@endsection
