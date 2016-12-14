<table class="table is-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Members</th>
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
                <td>
                    @foreach ($team->members as $member)
                        {{ $member->username }}@if (!$loop->last), @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>