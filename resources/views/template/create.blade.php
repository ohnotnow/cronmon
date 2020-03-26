@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Add new template</span>
        </h4>
    </div>
    <form method="POST" action="{{{ route('job.store') }}}">
        {{ csrf_field() }}
        @include('template.partials.form')
        <button class="button is-primary is-outlined" type="submit">Create new job</button>
        <a class="button" href="{{{ route('home') }}}">Cancel</a>
    </form>
</div>
@endsection
