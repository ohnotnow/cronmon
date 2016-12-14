@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Edit team
    </h2>
    <form method="POST" action="{{{ route('team.update', $team->id) }}}">
        {{ csrf_field() }}
        @include('team.partials.form')
        <button class="button is-primary is-outlined" type="submit">Update</button>
        <a class="button" href="{{{ route('profile.show') }}}">Cancel</a>
    </form>
@endsection
