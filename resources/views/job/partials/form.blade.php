        @if (Auth::user()->is_admin)
            @if ($job->user)
                <label>Owner</label>
                <p class="control">
                    <span class="select">
                        <select name="user_id">
                            @foreach ($users as $user)
                                <option value="{{{ $user->id }}}" @if ($job->user_id == $user->id) selected @endif>
                                    {{{ $user->username }}}
                                </option>
                            @endforeach
                        </select>
                    </span>
                </p>
            @endif
        @endif
        <label>Friendly name</label>
        <p class="control">
            <input class="input" type="text" name="name" value="{{ old('name', $job->name) }}" placeholder="Just an easy way to identify the job" required>
        </p>
        <label>Runs Every</label>
        <div class="columns">
            <div class="column">
            <p class="control">
                <input class="input" type="number" name="period" value="{{ old('period', $job->period) }}" min="1" required>
            </p>
            </div>
            <div class="column">
            <p class="control">
                <span class="select">
                    <select name="period_units">
                        @foreach ($job->units as $uName => $uTitle)
                            <option value="{{ $uName }}" @if ($job->period_units == $uName) selected @endif>
                                {{ $uTitle }}
                            </option>
                        @endforeach
                    </select>
                </span>
            </p>
            </div>
        </div>
        <label>
            Grace Period
        </label>
        <div class="columns">
            <div class="column">
            <p class="control">
                <input class="input" type="number" name="grace" value="{{ old('grace', $job->grace) }}" min="1" required>
            </p>
            </div>
            <div class="column">
            <p class="control">
                <span class="select">
                    <select name="grace_units">
                        @foreach ($job->units as $uName => $uTitle)
                            <option value="{{ $uName }}" @if ($job->grace_units == $uName) selected @endif>
                                {{ $uTitle }}
                            </option>
                        @endforeach
                    </select>
                </span>
            </p>
            </div>
        </div>
        <label>Email address to alert (Leave blank to use your own. You can use a comma-seperated list if you want)</label>
        <p class="control">
            <input class="input" type="text" name="email" value="{{ old('email', $job->email) }}" placeholder="{{ Auth::user()->email }}">
        </p>
        <label>Team</label>
        <p class="control">
            <span class="select">
                <select name="team_id">
                    <option value="-1">None</option>
                    @foreach (Auth::user()->teams as $team)
                        <option value="{{ $team->id }}" @if ($job->team_id == $team->id) selected @endif>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </span>
        </p>
        <label>Notes</label>
        <p class="control">
            <textarea class="textarea" name="notes">{{ $job->notes }}</textarea>
        </p>
        <br />
        <p class="control">
            <label class="checkbox">
                <input type="hidden" name="is_silenced" value="0">
                <input type="checkbox" name="is_silenced" value="1" @if ($job->is_silenced) checked @endif>
                Alarm Silenced
            </label>
        </p>
        <br />
        <p class="control">
            <label class="checkbox">
                <input type="hidden" name="is_logging" value="0">
                <input type="checkbox" name="is_logging" value="1" @if ($job->is_logging) checked @endif>
                Record Runs
            </label>
        </p>
        @if ($job->id)
            <hr />
            <p class="control">
                <label class="checkbox">
                    <input type="checkbox" name="regenerate_uuid" value="1">
                    Generate new UUID/URI?
                </label>
            </p>
        @endif

        <hr />
