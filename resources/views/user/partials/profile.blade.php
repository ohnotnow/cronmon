    <div class="columns">
        <div class="column">
            <h4 class="title is-4">Username</h4>
            <p class="subtitle">
                 {{ $user->username }}
            </p>
        </div>
        <div class="column">
            <h4 class="title is-4">Email</h4>
            <p class="subtitle">
                @if (Auth::user()->is_admin)
                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                @else
                    {{{ $user->email }}}
                @endif
            </p>
        </div>
        <div class="column">
            <h4 class="title is-4">Admin?</h4>
            <p class="subtitle">
                {{{ $user->is_admin ? 'Yes' : 'No' }}}
            </p>
        </div>
        <div class="column">
            <h4 class="title is-4">Silenced Alarms?</h4>
            <p class="subtitle">
                {{{ $user->is_silenced ? 'Yes' : 'No' }}}
            </p>
        </div>
    </div>
