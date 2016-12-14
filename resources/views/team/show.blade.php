@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Team details
        <a class="button is-pulled-right" href="{{{ route('team.edit', $team->id) }}}">Edit</a>
    </h2>
    <h3 class="title is-3">
        Name
    </h3>
    <p class="subtitle">
        {{ $team->name }}
    </p>
    <br />
    <h3 class="title is-3">
        Members
        <a class="button" href="{{{ route('teammember.edit', $team->id) }}}">Edit</a>
    </h3>
    <p class="subtitle">
        <ul class="is-unstyled">
        @foreach ($team->members as $member)
            <li>
                @if (Auth::user()->is_admin)
                    <a href="{{{ route('user.show', $member->id) }}}">
                        {{ $member->username }}
                    </a>
                @else
                    {{ $member->username }}
                @endif
            </li>
        @endforeach
        </ul>
    </p>
    <br />
    <h3 class="title is-3">
        Jobs
    </h3>
    @include('job.partials.index', ['jobs' => $team->jobs])
@endsection
