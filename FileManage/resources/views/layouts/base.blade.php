<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plugin FileManage</title>

    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/file-manage.css') }}"> --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/file-manage.js') }}"></script> --}}
</body>

</html>