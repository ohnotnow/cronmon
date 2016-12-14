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
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/fa/css/font-awesome.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="/favicon.ico">
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">

        @include('partials.navbar')

            <div class="section">
                <div class="container">
                    @include('partials.errors')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="/js/all.js"></script>
    <script>
        $(document).ready(function () {
            $('.datatable').DataTable({
                "paging":   false,
                "ordering": false,
                "info": false,
            });
            $('#yourjobs').click(function() {
                $('#teamjobstab').removeClass('is-active')
                $('#teamjobstable').hide();
                $('#yourjobstab').addClass('is-active');
                $('#yourjobstable').show();
            });
            $('#teamjobs').click(function() {
                $('#yourjobstab').removeClass('is-active');
                $('#yourjobstable').hide();
                $('#teamjobstab').addClass('is-active')
                $('#teamjobstable').show();
            });
        });
    </script>
</body>
</html>
