@extends('layouts.app')

<!-- Main Content -->
@section('content')
    <div class="columns">
        <div class="column"></div>
        <div class="column">
            <h2 class="title is-2">Reset Password</h2>
            <form method="POST" action="{{ url('/password/email') }}">
                {{ csrf_field() }}
                <label>E-Mail Address</label>
                <p class="control">
                    <input name="email" type="email" class="input" value="{{ old('email') }}" required>
                </p>
                <p class="control">
                    <button type="submit" class="button is-primary is-outlined">
                        Send Password Reset Link
                    </button>
                </p>
            </form>
        </div>
        <div class="column"></div>
    </div>
@endsection
