@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        All teams
    </h2>
    <table class="table is-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>No. Members</th>
                <th>No. Jobs</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $team)
                <tr>
                    <td>
                        <a href="{{{ route('team.show', $team->id) }}}">
                            {{ $team->name }}
                        </a>
                    </td>
                    <td>{{ $team->members()->count() }}</td>
                    <td>{{ $team->jobs()->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
