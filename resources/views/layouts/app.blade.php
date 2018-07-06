<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/cronmon.css" rel="stylesheet">

    <link rel="shortcut icon" href="/favicon.ico">
    <!-- Scripts -->
    <script>
        window.Laravel = @json(['csrfToken' => csrf_token()])
    </script>
</head>
<body class="bg-grey-lightest">
    <div id="app">

        @include('partials.navbar')

        <div class="container mx-auto pt-8 mb-16">
                @include('partials.errors')
                @yield('content')
        </div>

    </div>

    <script src="/js/app.js"></script>
</body>
</html>
