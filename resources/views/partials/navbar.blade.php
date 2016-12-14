<nav class="nav has-shadow">
  <div class="nav-left">
    <a class="nav-item is-brand" href="/">
      {{ config('app.name') }}
    </a>
    @if (Auth::user() and Auth::user()->is_admin)
      <a class="nav-item" href="{{ route('user.index') }}">
        Users
      </a>
      <a class="nav-item" href="{{ route('job.index') }}">
        Jobs
      </a>
      <a class="nav-item" href="{{ route('team.index') }}">
        Teams
      </a>
    @endif
  </div>

  <span class="nav-toggle">
    <span></span>
    <span></span>
    <span></span>
  </span>

  @if (Auth::user())
    <div class="nav-center">
      <a class="nav-item" href="{{{ route('profile.show') }}}">
        My account
        @if (Auth::user()->is_silenced)
          <span class="icon" title="Account is silenced">
            <i class="fa fa-bell-o" aria-hidden="true"></i>
          </span>
        @endif
      </a>
    </div>
    <div class="nav-right nav-menu">
      <form class="nav-item" method="POST" action="{{ route('logout') }}">
        {{ csrf_field() }}
        <button class="button">Log out</button>
      </form>
    </div>
  @endif
</nav>