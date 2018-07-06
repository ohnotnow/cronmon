@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center">
        <div class="shadow p-8 bg-white w-1/2">
            <form method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}

                <div class="mb-2">
                    <label class="label">Username or Email</label>
                    <input class="shadow appearance-none border focus:border-grey-dark rounded w-full py-2 px-3 text-grey-darker leading-tight" type="text" name="login" required autofocus>
                </div>
                <div class="mb-8">
                    <label class="label">Password</label>
                    <input class="shadow appearance-none border focus:border-grey-dark rounded w-full py-2 px-3 text-grey-darker leading-tight" type="password" name="password" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-orange hover:bg-orange-dark focus:bg-orange-dark text-white font-bold py-2 px-4 rounded">Log in</button>
                    <a class="text-orange-dark hover:text-orange-darker is-pulled-right" href="{{ url('/password/reset') }}">
                        Forgot Your Password?
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
