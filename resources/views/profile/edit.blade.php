@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Edit my details
    </h2>
    <form method="POST" action="{{{ route('profile.update') }}}">
        {{ csrf_field() }}
        @include('user.partials.form')
        <button class="button is-primary is-outlined" type="submit">Update</button>
        <a class="button" href="{{{ route('profile.show') }}}">Cancel</a>
    </form>
@endsection
