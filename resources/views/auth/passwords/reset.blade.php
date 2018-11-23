@extends('layouts.app')

@section('content')
    <div class="columns">
        <div class="column"></div>
        <div class="column">
            <h2 class="title is-2">Reset Password</h2>
            <form method="POST" action="{{ url('/password/reset') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
                <label>E-Mail Address</label>
                <p class="cotrol">
                    <input type="email" class="input" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                </p>
                <label>New Password</label>
                <p class="control">
                    <input class="input" type="password" name="password" id="password" required>
                </p>
                <label>Confirm New Password</label>
                <p class="control">
                    <input class="input" id="password-confirm" type="password" name="password_confirmation" required>
                </p>
                <p class="control">
                    <button type="submit" class="button is-primary is-outlined">Reset Password</button>
                </p>
            </form>
        </div>
        <div class="column"></div>
    </div>
@endsection
