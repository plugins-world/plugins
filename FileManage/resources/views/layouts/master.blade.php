<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plugin FileManage</title>

    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/file-manage.css') }}"> --}}

    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    <script>
        tailwind.config = {
            content: [],
            theme: {
                extend: {},
            },
            plugins: [],
        }
    </script>
</head>

<body>
    @yield('content')

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/file-manage.js') }}"></script> --}}
</body>

</html>