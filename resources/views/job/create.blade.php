@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Add new job
    </h2>
    <form method="POST" action="{{{ route('job.store') }}}">
        {{ csrf_field() }}
        @include('job.partials.form')
        <button class="button is-primary is-outlined" type="submit">Create new job</button>
        <a class="button" href="{{{ route('job.index') }}}">Cancel</a>
    </form>
@endsection
