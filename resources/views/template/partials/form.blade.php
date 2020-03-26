<div class="mb-8">
        @if (Auth::user()->is_admin)
            @if ($template->user)
                <label class="title block">Owner</label>
                    <span class="inline-block relative w-1/3">
                        <select name="user_id" class="block appearance-none w-full bg-white border border-grey-light hover:border-grey px-4 py-2 pr-8 rounded shadow leading-tight">
                            @foreach ($users as $user)
                                <option value="{{{ $user->id }}}" @if ($template->user_id == $user->id) selected @endif>
                                    {{{ $user->username }}}
                                </option>
                            @endforeach
                        </select>
                  <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                  </div>
                                    </span>
            @endif
        @endif
</div>
<div class="mb-8">
        <label class="title">Friendly name</label>
        <p class="control">
            <input class="input" type="text" name="name" value="{{ old('name', $template->name) }}" placeholder="Eg, Weekly Backup" required>
        </p>
</div>
<div class="mb-8">
        <label class="title">Runs Every</label>
        <div class="flex">
                <input class="input" type="number" name="period" value="{{ old('period', $template->period) }}" min="1" required>
                <span class="inline-block relative w-64">
                    <select name="period_units" class="block appearance-none w-full bg-white border border-grey-light hover:border-grey px-4 py-2 pr-8 rounded shadow leading-tight">
                        @foreach ($template->units as $uName => $uTitle)
                            <option value="{{ $uName }}" @if ($template->period_units == $uName) selected @endif>
                                {{ $uTitle }}
                            </option>
                        @endforeach
                    </select>
                                      <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                  </div>
                </span>
        </div>
        <div class="mt-2">
            <label class="title"><span class="text-grey-dark font-black">OR</span> use a cron expression (this takes precedence over the above if set)</label>
            <p class="control">
                <input class="input" type="text" name="cron_schedule" value="{{ old('cron_schedule', $template->cron_schedule) }}" placeholder="Eg, */15 * * * *">
            </p>
        </div>

</div>
<div class="mb-8">
        <label class="title">
            Grace Period
        </label>
            <div class="flex">
                <input class="input" type="number" name="grace" value="{{ old('grace', $template->grace) }}" min="1" required>
                <span class="inline-block relative w-64">
                    <select name="grace_units" class="block appearance-none w-full bg-white border border-grey-light hover:border-grey px-4 py-2 pr-8 rounded shadow leading-tight">
                        @foreach ($template->units as $uName => $uTitle)
                            <option value="{{ $uName }}" @if ($template->grace_units == $uName) selected @endif>
                                {{ $uTitle }}
                            </option>
                        @endforeach
                    </select>
                                  <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                  </div>
                </span>
            </div>
</div>
<div class="mb-8">
        <label class="title">Email address to alert (Leave blank to use your own. You can use a comma-seperated list if you want)</label>
        <p class="control">
            <input class="input" type="text" name="email" value="{{ old('email', $template->email) }}" placeholder="{{ Auth::user()->email }}">
        </p>
</div>
<div class="mb-8">
        <label class="title">Fallback email address to alert (this is used after {{ config('cronmon.fallback_delay') }}hrs if the job is still alerting. Leave blank to ignore)</label>
            <input class="input" type="text" name="fallback_email" value="{{ old('fallback_email', $template->fallback_email) }}">
</div>
<div class="mb-8">
        <label class="title block">Team</label>
            <span class="inline-block relative w-1/3">
                <select name="team_id" class="block appearance-none w-full bg-white border border-grey-light hover:border-grey px-4 py-2 pr-8 rounded shadow leading-tight">
                    <option value="-1">None</option>
                    @foreach (Auth::user()->teams as $team)
                        <option value="{{ $team->id }}" @if ($template->team_id == $team->id) selected @endif>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
                  <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                  </div>
            </span>
</div>
<div class="mb-8">
        <label class="title">Notes</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight" name="notes">{{ $template->notes }}</textarea>
</div>
<div class="mb-8">
            <label class="title">
                <input type="hidden" name="is_silenced" value="0">
                <input type="checkbox" name="is_silenced" value="1" @if ($template->is_silenced) checked @endif>
                Alarm Silenced
            </label>
        <br />
        <p class="control">
            <label class="title">
                <input type="hidden" name="is_logging" value="0">
                <input type="checkbox" name="is_logging" value="1" @if ($template->is_logging) checked @endif>
                Record Runs
            </label>
        </p>
        @if ($template->id)
                <label class="title">
                    <input type="checkbox" name="regenerate_uuid" value="1">
                    Generate new UUID/URI?
                </label>
        @endif
</div>
        <hr />
