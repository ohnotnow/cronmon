@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8">

    <h2 class="text-orange-dark font-light flex justify-between mb-8 bg-orange-lightest -mx-8 -mt-8">
        <span class="flex-1 p-4">
            Template details
        </span>
        <span class="flex-1 p-4 text-right text-base">
            <a class="hover:bg-orange hover:text-white focus:bg-orange border border-orange text-orange-dark font-bold py-2 px-4 rounded" href="{{{ route('template.edit', $template->id) }}}">Edit template</a>
        </span>
    </h2>
    <div class="md:flex justify-between mb-4">
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Template name
            </h3>
            <p class="subtitle">
                {{ $template->name }}
            </p>
        </div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Owner
            </h3>
            <p class="">
                @if (Auth::user()->is_admin)
                    <a class="text-orange-dark" href="{{{ route('user.show', $template->user->id) }}}">
                        {{ $template->user->username }}
                    </a>
                @else
                    {{ $template->user->username }}
                @endif
            </p>
        </div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Created
            </h3>
            <p class="subtitle">
                {{ $template->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
    <div class="md:flex justify-between mb-4">
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Schedule
            </h3>
            <p class="subtitle">
                {{ $template->getSchedule() }}
            </p>
        </div>
        <div class="flex-1 p-4"></div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Grace
            </h3>
            <p class="subtitle">
                {{ $template->grace }} {{ $template->humanGraceUnits() }}
            </p>
        </div>
    </div>
    <div class="md:flex justify-between mb-4">
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Alert goes to
            </h3>
            <p class="subtitle">
                {{ $template->getEmail() }}
                @if ($template->fallback_email)
                    <br>(fallback of {{ $template->fallback_email }})
                @endif
            </p>
        </div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Team
            </h3>
            <p class="subtitle">
                @if ($template->team_id)
                    <a href="{{{ route('team.show', $template->team_id) }}}">
                        {{ $template->getTeamName() }}
                    </a>
                @else
                    {{ $template->getTeamName() }}
                @endif
            </p>
        </div>
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                Notes
            </h3>
            <p class="subtitle">
                {{ $template->notes }}
            </p>
        </div>
    </div>
    <div class="md:flex justify-between mb-4">
        <div class="flex-1 p-4">
            <h3 class="text-grey-dark text-xl font-light mb-2">
                URI
            </h3>
            <p class="subtitle">{{ $template->uri() }}</p>
            <p>
                Bash : <code>curl -X POST {{ $template->uri() }}</code>
            </p>
            <p>
                Powershell : <code>wget {{ $template->uri() }}</code>
            </p>
            <p>
                Laravel : <code>Http::post("{{ $template->uri() }}")</code>
            </p>
            <p>
                Python3 : <code>requests.post("{{ $template->uri() }}")</code>
            </p>
        </div>
    </div>
</div>
@endsection
