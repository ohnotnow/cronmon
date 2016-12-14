@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Add new team
    </h2>
    <form method="POST" action="{{{ route('team.store') }}}">
        {{ csrf_field() }}
        @include('team.partials.form')
        <button class="button is-primary is-outlined" type="submit">Create new team</button>
        <a class="button" href="{{{ route('profile.show') }}}">Cancel</a>
    </form>
@endsection
