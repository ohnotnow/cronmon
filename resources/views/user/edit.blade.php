@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Edit user
        <form method="POST" action="{{{ route('user.destroy', $user->id) }}}">
        	{{{ csrf_field() }}}
        	<button class="button is-danger is-outlined is-pulled-right">Delete user</button>
        </form>
    </h2>
    <form method="POST" action="{{{ route('user.update', $user->id) }}}">
        {{ csrf_field() }}
        @include('user.partials.form')
        <button class="button is-primary is-outlined" type="submit">Update user</button>
        <a class="button" href="{{{ route('user.index') }}}">Cancel</a>
    </form>
@endsection
