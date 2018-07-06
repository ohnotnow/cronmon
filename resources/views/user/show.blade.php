@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">User details for {{ $user->username }}</span>
            <span class="flex-1 text-right">
                <a class="button text-base" href="{{{ route('user.edit', $user->id) }}}">Edit user</a>
            </span>
        </h4>
    </div>
    @include('user.partials.profile')
</div>

<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Jobs</span>
        </h4>
    </div>
    @include('job.partials.index', ['jobs' => $user->getAvailableJobs()])
</div>

@endsection
