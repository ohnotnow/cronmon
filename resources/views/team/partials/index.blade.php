<div class="flex border-b-2 border-orange p-4">
    <span class="flex-1 font-semibold">Name</span>
    <span class="flex-1 flex-grow font-semibold">Members</span>
</div>
@foreach ($teams as $team)
    <div class="flex p-4 mb-4">
        <span class="flex-1">
            <a class="text-orange" href="{{{ route('team.show', $team->id) }}}">
                {{ $team->name }}
            </a>
        </span>
        <span class="flex-1 flex-grow">
            @foreach ($team->members as $member)
                {{ $member->username }}@if (!$loop->last), @endif
            @endforeach
        </span>
    </div>
@endforeach