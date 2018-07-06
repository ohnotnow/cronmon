@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Edit User</span>
            <form class="flex-1 text-right text-base" method="POST" action="{{{ route('user.destroy', $user->id) }}}">
                {{{ csrf_field() }}}
                <button class="button">Delete user</button>
            </form>
        </h4>
    </div>
    <form method="POST" action="{{{ route('user.update', $user->id) }}}">
        {{ csrf_field() }}
        @include('user.partials.form')
        <button class="button is-primary is-outlined" type="submit">Update user</button>
        <a class="button" href="{{{ route('user.index') }}}">Cancel</a>
    </form>
</div>
@endsection
