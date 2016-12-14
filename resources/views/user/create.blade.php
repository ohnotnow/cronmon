@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Add new user
    </h2>
    <form method="POST" action="{{{ route('user.store') }}}">
        {{ csrf_field() }}
        @include('user.partials.form')
        <button class="button is-primary is-outlined" type="submit">Create new user</button>
        <a class="button" href="{{{ route('user.index') }}}">Cancel</a>
    </form>
@endsection
