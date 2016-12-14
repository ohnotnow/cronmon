@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Job details
        @if ($job->isAwol())
            @if ($job->is_silenced)
                <i class="fa fa-bell-o animated infinite tada" title="Awol - silenced"></i>
            @else
                <i class="fa fa-bell animated infinite tada" title="Awol - alerting"></i>
            @endif
        @endif
        <a class="button is-pulled-right" href="{{{ route('job.edit', $job->id) }}}">Edit job</a>
    </h2>
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">
                Job name
            </h3>
            <p class="subtitle">
                {{ $job->name }}
            </p>
        </div>
        <div class="column">
            <h3 class="title is-3">
                Owner
            </h3>
            <p class="subtitle">
                @if (Auth::user()->is_admin)
                    <a href="{{{ route('user.show', $job->user->id) }}}">
                        {{ $job->user->username }}
                    </a>
                @else
                    {{ $job->user->username }}
                @endif
            </p>
        </div>
        <div class="column">
            <h3 class="title is-3">
                Created
            </h3>
            <p class="subtitle">
                {{ $job->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">
                Last seen
            </h3>
            <p class="subtitle" title="{{ $job->last_run->format('d/m/Y H:i') }}">
                {{ $job->last_run->diffForHumans() }}
            </p>
        </div>
        <div class="column">
            <h3 class="title is-3">
                Schedule
            </h3>
            <p class="subtitle">
                {{ $job->getSchedule() }}
            </p>
        </div>
        <div class="column">
            <h3 class="title is-3">
                Grace
            </h3>
            <p class="subtitle">
                {{ $job->grace }} {{ $job->humanGraceUnits() }}
            </p>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">
                Alert goes to
                @if ($job->is_silenced)
                    (silenced)
                @endif
            </h3>
            <p class="subtitle">
                {{ $job->getEmail() }}
            </p>
        </div>
        <div class="column">
            <h3 class="title is-3">
                Team
            </h3>
            <p class="subtitle">
                @if ($job->team_id)
                    <a href="{{{ route('team.show', $job->team_id) }}}">
                        {{ $job->getTeamName() }}
                    </a>
                @else
                    {{ $job->getTeamName() }}
                @endif
            </p>
        </div>
        <div class="column">
            <h3 class="title is-3">
                Notes
            </h3>
            <p class="subtitle">
                {{ $job->notes }}
            </p>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">
                URI
            </h3>
            <p class="subtitle">{{ $job->uri() }}</p>
            <p>
                Bash : <code>curl -s {{ $job->uri() }}</code>
            </p>
            <p>
                Powershell : <code>wget {{ $job->uri() }}</code>
            </p>
            <p>
                PHP : <code>file_get_contents("{{ $job->uri() }}")</code>
            </p>
            <p>
                Python3 : <code>urllib.request.urlopen("{{ $job->uri() }}").read()</code>
            </p>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">
                Run history
            </h3>
            <ul class="is-unstyled">
            @foreach ($job->getRecentPings() as $ping)
                <li>
                    {{ $ping->created_at->format('d/m/Y H:i') }}
                    @if ($ping->data)
                        -- {{ $ping->data }}
                    @endif
                </li>
            @endforeach
            </ul>
        </div>
    </div>
@endsection
