<nav class="flex items-center justify-between flex-wrap border-b-2 border-orange bg-grey-lightest p-6 shadow">
  <div class="md:flex md:flex-grow md:items-center">
    <div class="">
      <a class="font-semibold pr-4 text-orange-dark hover:text-orange-dark text-xl tracking-tight" href="/">
        {{ config('app.name') }}
      </a>
    </div>
    <div>
      @if (Auth::user())
          <a class="text-orange hover:text-orange-dark text-center pr-2" href="{{{ route('profile.show') }}}">
            My account
            @if (Auth::user()->is_silenced)
              <span class="icon" title="Account is silenced">
                <i class="fa fa-bell-o" aria-hidden="true"></i>
              </span>
            @endif
          </a>
          @if (Auth::user()->is_admin)
            <a class="text-orange hover:text-orange-dark pr-2" href="{{ route('user.index') }}">
              Users
            </a>
            <a class="text-orange hover:text-orange-dark pr-2" href="{{ route('job.index') }}">
              Jobs
            </a>
            <a class="text-orange hover:text-orange-dark pr-2" href="{{ route('team.index') }}">
              Teams
            </a>
          @endif
      @endif
    </div>
  </div>

  @if (Auth::user())
    <div class="flex-shrink text-right">
      <form class="" method="POST" action="{{ route('logout') }}">
        {{ csrf_field() }}
        <button class="text-orange hover:text-orange-dark">Log out</button>
      </form>
    </div>
  @endif
</nav>