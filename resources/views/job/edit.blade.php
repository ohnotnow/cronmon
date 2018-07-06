@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
    <div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
        <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Edit job {{ $job->name }}</span>
            <form class="flex-1 text-right" method="POST" action="{{{ route('job.destroy', $job->id) }}}">
                {{{ csrf_field() }}}
                <button class="button-danger text-base">Delete job</button>
            </form>
        </h4>
    </div>
    <form method="POST" action="{{{ route('job.update', $job->id) }}}">
        {{ csrf_field() }}
        @include('job.partials.form')
        <button class="button" type="submit">Update</button>
        <a class="button bg-grey-lightest hover:bg-grey-lightest hover:text-grey" href="{{{ route('job.show', $job->id) }}}">Cancel</a>
    </form>
</div>
@endsection
