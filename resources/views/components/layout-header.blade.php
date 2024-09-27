<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('BMS') }}</title>
{{-- cache control --}}
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
{{-- cache control --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css?v=' . time()) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css?v={{ time() }}" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="imges/bank.png?v={{ time() }}">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
