    <div class="flex mb-8">
        <div class="flex-1">
            <h4 class="title mb-2">Username</h4>
            <p class="subtitle">
                 {{ $user->username }}
            </p>
        </div>
        <div class="flex-1">
            <h4 class="title mb-2">Email</h4>
            <p class="subtitle">
                @if (Auth::user()->is_admin)
                    <a class="text-orange" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                @else
                    {{{ $user->email }}}
                @endif
            </p>
        </div>
        <div class="flex-1">
            <h4 class="title mb-2">Admin?</h4>
            <p class="subtitle">
                {{{ $user->is_admin ? 'Yes' : 'No' }}}
            </p>
        </div>
        <div class="flex-1">
            <h4 class="title mb-2">Silenced Alarms?</h4>
            <p class="subtitle">
                {{{ $user->is_silenced ? 'Yes' : 'No' }}}
            </p>
        </div>
    </div>
