@extends('layouts.app')

@section('content')
            <div class="bg-white shadow p-8 mb-4">

    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
    <h2 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
        <span class="flex-1">My details</span>
        <span class="flex-1 text-right">
            <a class="button text-base" href="{{{ route('profile.edit') }}}">Edit</a>
        </span>
    </h2>
    </div>
    @include('user.partials.profile')

            </div>
<div class="bg-white shadow p-8 mb-4">

<div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
    <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">My Teams</span>
            <span class="flex-1 text-right">
                <a class="button text-base" href="{{{ route('team.create') }}}">Add new team</a>
            </span>
    </h4>
</div>
@include('team.partials.index', ['teams' => $user->teams])
</div>

<div class="bg-white shadow p-8">

<div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
    <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">My Jobs</h4>
</div>
@include('job.partials.index', ['jobs' => $user->getAvailableJobs()])
</div>
@endsection
