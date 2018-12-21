@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Edit team</span>
            <form class="flex-1 text-right" method="POST" action="{{{ route('team.delete', $team->id) }}}">
                {{{ csrf_field() }}}
                @method('DELETE')
                <button class="button-danger text-base">Delete team</button>
            </form>
        </h4>
    </div>
    <form method="POST" action="{{{ route('team.update', $team->id) }}}">
        {{ csrf_field() }}
        @include('team.partials.form')
        <button class="button is-primary is-outlined" type="submit">Update</button>
        <a class="button" href="{{{ route('profile.show') }}}">Cancel</a>
    </form>
</div>
@endsection
