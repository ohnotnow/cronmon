@extends('layouts.app')

@section('content')
    <h2 class="title is-2">
        Edit {{ $team->name }} members
    </h2>
    <form method="POST" action="{{{ route('teammember.update', $team->id) }}}">
        {{ csrf_field() }}
        <h3 class="title is-3">
            Current Members
        </h3>
        <table class="table is-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Remove?</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($team->members as $member)
                    <tr>
                        <td>{{ $member->username }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <p class="control">
                              <label class="checkbox">
                                <input type="checkbox" name="remove[{{ $member->id }}]" value="{{ $member->id }}">
                              </label>
                            </p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h3 class="title is-3">
            Add a new member
        </h3>
        <p class="control">
          <span class="select">
            <select name="add">
                <option value=""></option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                @endforeach
            </select>
          </span>
        </p>
        <button class="button is-primary is-outlined" type="submit">Update members</button>
        <a class="button" href="{{{ route('team.show', $team->id) }}}">Cancel</a>
    </form>
@endsection
