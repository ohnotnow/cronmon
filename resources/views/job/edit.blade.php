@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Edit job {{ $job->name }}
        <form method="POST" action="{{{ route('job.destroy', $job->id) }}}">
        	{{{ csrf_field() }}}
        	<button class="button is-danger is-outlined is-pulled-right">Delete job</button>
        </form>
    </h2>
    <form method="POST" action="{{{ route('job.update', $job->id) }}}">
        {{ csrf_field() }}
        @include('job.partials.form')
        <button class="button is-primary is-outlined" type="submit">Update</button>
        <a class="button" href="{{{ route('job.show', $job->id) }}}">Cancel</a>
    </form>
@endsection
