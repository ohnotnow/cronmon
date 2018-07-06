@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Edit {{ $team->name }} members</span>
        </h4>
    </div>
    <form method="POST" action="{{{ route('teammember.update', $team->id) }}}">
        {{ csrf_field() }}
        <h3 class="title mb-2">
            Current Members
        </h3>
        <div class="flex p-4 border-b-2 border-orange">
            <span class="flex-1">
                Name
            </span>
            <span class="flex-1">
                Email
            </span>
            <span class="flex-1">
                Remove?
            </span>
        </div>
        @foreach ($team->members as $member)
            <div class="flex p-4">
                <span class="flex-1">
                    {{ $member->username }}
                </span>
                <span class="flex-1">
                    {{ $member->email }}
                </span>
                <span class="flex-1">
                    <label class="checkbox">
                        <input type="checkbox" name="remove[{{ $member->id }}]" value="{{ $member->id }}">
                    </label>
                </span>
            </div>
        @endforeach
        <h3 class="title mb-2 mt-4">
            Add a new member
        </h3>
        <div class="block mb-8">
            <span class="inline-block relative w-1/3">
                <select name="add" class="block appearance-none w-full bg-white border border-grey-light hover:border-grey px-4 py-2 pr-8 rounded shadow leading-tight">
                    <option value=""></option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </span>
        </div>
        <button class="button" type="submit">Update members</button>
        <a class="button" href="{{{ route('team.show', $team->id) }}}">Cancel</a>
    </form>
</div>
@endsection
