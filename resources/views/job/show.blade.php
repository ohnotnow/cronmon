@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8">

    <h2 class="text-orange-dark font-light flex justify-between mb-8 bg-orange-lightest -mx-8 -mt-8">
        <span class="flex-1 p-4">
            Job details
            @if ($job->isAwol())
                @if ($job->is_silenced)
                    <i class="fa fa-bell-o animated infinite tada" title="Awol - silenced"></i>
                @else
                    <i class="fa fa-bell animated infinite tada" title="Awol - alerting"></i>
                @endif
            @endif
        </span>
        <span class="flex-1 p-4 text-right text-base">
            <a class="hover:bg-orange hover:text-white focus:bg-orange border border-orange text-orange-dark font-bold py-2 px-4 rounded" href="{{{ route('job.edit', $job->id) }}}">Edit job</a>
        </span>
    </h2>
    <div class="md:flex justify-between mb-4">
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Job name
            </h3>
            <p class="subtitle">
                {{ $job->name }}
            </p>
        </div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Owner
            </h3>
            <p class="">
                @if (Auth::user()->is_admin)
                    <a class="text-orange-dark" href="{{{ route('user.show', $job->user->id) }}}">
                        {{ $job->user->username }}
                    </a>
                @else
                    {{ $job->user->username }}
                @endif
            </p>
        </div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Created
            </h3>
            <p class="subtitle">
                {{ $job->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
    <div class="md:flex justify-between mb-4">
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Last seen
            </h3>
            <p class="subtitle" title="{{ $job->last_run->format('d/m/Y H:i') }}">
                {{ $job->last_run->diffForHumans() }}
            </p>
        </div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Schedule
            </h3>
            <p class="subtitle">
                {{ $job->getSchedule() }}
            </p>
        </div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Grace
            </h3>
            <p class="subtitle">
                {{ $job->grace }} {{ $job->humanGraceUnits() }}
            </p>
        </div>
    </div>
    <div class="md:flex justify-between mb-4">
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Alert goes to
                @if ($job->is_silenced)
                    (silenced)
                @endif
            </h3>
            <p class="subtitle">
                {{ $job->getEmail() }}
                @if ($job->fallback_email)
                    <br>(fallback of {{ $job->fallback_email }})
                @endif
            </p>
        </div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
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
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Notes
            </h3>
            <p class="subtitle">
                {{ $job->notes }}
            </p>
        </div>
    </div>
    <div class="md:flex justify-between mb-4">
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
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
    <div class="flex justify-between mb-4">
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
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
</div>
@endsection
