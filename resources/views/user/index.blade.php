@extends('layouts.app')

@section('content')
	<h2 class="title is-2">
		Current users
		<a class="button is-pulled-right" href="{{{ route('user.create') }}}">
			Add user
		</a>
	</h2>
	<table class="table is-striped">
		<thead>
			<tr>
				<th>Username</th>
				<th>Email</th>
				<th>No. Jobs</th>
				<th>Is Admin?</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $user)
				<tr>
					<td>
						<a href="{{{ route('user.show', $user->id) }}}">
							{{ $user->username }}
						</a>
						@if ($user->is_silenced)
						    <span class="icon is-small" title="Alarms silenced">
				        	    <i class="fa fa-bell-o"></i>
				        	</span>
						@endif
					</td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->jobs()->count() }}</td>
					<td>{{ $user->is_admin ? 'Yes' : 'No' }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection