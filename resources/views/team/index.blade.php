@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">All teams</span>
        </h4>
    </div>
    <div class="flex p-4 border-b-2 border-orange">
        <span class="font-semibold flex-1">
            Name
        </span>
        <span class="font-semibold flex-1">
            No. Members
        </span>
        <span class="font-semibold flex-1">
            No. Jobs
        </span>
    </div>
    @foreach ($teams as $team)
        <div class="flex p-4">
            <span class="flex-1">
                <a class="text-orange" href="{{{ route('team.show', $team->id) }}}">
                    {{ $team->name }}
                </a>
            </span>
            <span class="flex-1">
                {{ $team->members->count() }}
            </span>
            <span class="flex-1">
                {{ $team->jobs->count() }}
            </span>
        </div>
    @endforeach
</div>
@endsection
