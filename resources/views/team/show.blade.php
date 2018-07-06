@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Team details</span>
            <span class="flex-1 text-right">
                <a class="button text-base" href="{{{ route('team.edit', $team->id) }}}">Edit</a>
            </span>
        </h4>
    </div>
    <div class="mb-8">
        <h3 class="title is-3">
            Name
        </h3>
        <p class="subtitle">
            {{ $team->name }}
        </p>
    </div>
    <h3 class="title mb-2">
        Members
        <a class="text-orange" href="{{{ route('teammember.edit', $team->id) }}}">Edit</a>
    </h3>
    <p class="">
        <ul class="list-reset">
        @foreach ($team->members as $member)
            <li class="p-2">
                @if (Auth::user()->is_admin)
                    <a class="text-orange" href="{{{ route('user.show', $member->id) }}}">
                        {{ $member->username }}
                    </a>
                @else
                    {{ $member->username }}
                @endif
            </li>
        @endforeach
        </ul>
    </p>
</div>
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Team Jobs</span>
        </h4>
    </div>
    @include('job.partials.index', ['jobs' => $team->jobs])
</div>
@endsection
