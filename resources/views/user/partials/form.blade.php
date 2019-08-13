<div class="mb-8">
    <label class="title">Username</label>
    <input class="input" type="text" name="username" value="{{ old('username', $user->username) }}" required>
</div>

<div class="mb-8">
    <label class="title">Email Address</label>
    <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
</div>

<div class="mb-8">
    @if (Auth::user()->is_admin)
        <label class="title">
            <input class="checkbox" type="checkbox" value="1" name="is_admin" @if ($user->is_admin) checked @endif>
            Admin user?
        </label>
    @endif
</div>

<div class="mb-8">
    <label class="title">
        <input type="hidden" name="is_silenced" value="0">
        <input class="checkbox" type="checkbox" value="1" name="is_silenced" @if ($user->is_silenced) checked @endif>
        Silence Alarms?
    </label>
</div>

<div class="mb-8">
    <label class="title">
        <input type="hidden" name="new_api_key" value="0">
        <input class="checkbox" type="checkbox" value="1" name="new_api_key">
        Generate a new API key?
    </label>
</div>

<hr />
<div class="mb-8">
@if (Auth::user()->is_admin and $user->id)
    <label class="title">
        <input type="checkbox" class="checkbox" name="reset_password" value="1"> Reset users password?
    </label>
    <hr />
@endif
</div>