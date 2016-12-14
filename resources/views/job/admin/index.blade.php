@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        All Jobs
    </h2>
    <table class="table is-striped datatable">
        <thead>
            <tr>
                <th>Status</th>
                <th>Name</th>
                <th>Schedule</th>
                <th>Last Run</th>
                <th>Owner</th>
                <th>Group</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobs as $job)
                <tr>
                    <td>
                        @include('job.partials.status')
                    </td>
                    <td>
                        <a href="{{{ route('job.show', $job->id) }}}">
                            {{ $job->name }}
                        </a>
                    </td>
                    <td>{{ $job->getSchedule() }}</td>
                    <td>{{ $job->getLastRunDiff() }}</td>
                    <td>
                        <a href="{{{ route('user.show', $job->user_id) }}}">
                            {{ $job->user->username }}
                        </a>
                    </td>
                    <td>
                        @if ($job->team_id)
                            <a href="{{{ route('team.show', $job->team_id) }}}">
                                {{ $job->getTeamName() }}
                            </a>
                        @else
                            {{ $job->getTeamName() }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
