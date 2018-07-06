@extends('layouts.app')

@section('content')
<div class="bg-white shadow p-8 mb-4">
<div class="text-orange-dark font-light mb-8 bg-orange-lightest -mx-8 -mt-8">
    <h4 class="title text-orange-dark font-light text-2xl p-4 flex justify-between">
            <span class="flex-1">Current Users</span>
            <span class="flex-1 text-right">
                <a class="button text-base" href="{{{ route('user.create') }}}">Add user</a>
            </span>
    </h4>
</div>
<div class="hidden md:flex justify-between p-4 font-semibold border-b-2 border-orange">
	<span class="flex-1">Username</span>
	<span class="flex-1">Email</span>
	<span class="flex-1">No. Jobs</span>
	<span class="flex-1">Is Admin?</span>
</div>
@foreach ($users as $user)
	<div class="flex-col flex border-b-2 border-orange-lighter md:border-none md:flex-row justify-between p-4 hover:bg-orange-lightest">
		<span class="flex-1">
			<a class="text-orange" href="{{{ route('user.show', $user->id) }}}">
				{{ $user->username }}
			</a>
			@if ($user->is_silenced)
				<span class="icon is-small" title="Alarms silenced">
					<i class="fa fa-bell-o"></i>
				</span>
			@endif
		</span>
		<span class="flex-1">
			{{ $user->email }}
		</span>
		<span class="flex-1">
			<span class="inline font-semibold md:hidden">No. Jobs: </span>
			{{ $user->jobs()->count() }}
		</span>
		<span class="flex-1">
			<span class="inline font-semibold md:hidden">Admin? </span>
			{{ $user->is_admin ? 'Yes' : 'No' }}
		</span>
	</div>
@endforeach
</div>

@endsection