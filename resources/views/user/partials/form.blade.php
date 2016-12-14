<label>Username</label>
<p class="control">
	<input class="input" type="text" name="username" value="{{ old('username', $user->username) }}" required>
</p>
<label>Email Address</label>
<p class="control">
	<input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
</p>
@if (Auth::user()->is_admin)
	<label class="checkbox">
		<input class="checkbox" type="checkbox" value="1" name="is_admin" @if ($user->is_admin) checked @endif>
		Admin user?
	</label>
@endif
<label class="checkbox">
    <input type="hidden" name="is_silenced" value="0">
    <input class="checkbox" type="checkbox" value="1" name="is_silenced" @if ($user->is_silenced) checked @endif>
    Silence Alarms?
</label>
<hr />
@if (Auth::user()->is_admin and $user->id)
    <label class="checkbox">
        <input type="checkbox" class="checkbox" name="reset_password" value="1"> Reset users password?
    </label>
    <hr />
@endif