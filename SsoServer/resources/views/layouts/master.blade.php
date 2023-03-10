<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
    @include('SsoServer::commons.head', [
        'title' => 'Plugin SsoServer',
    ])

    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/sso-server.css') }}"> --}}
</head>

<body>
    <div class="position-relative">
        @yield('content')

        @include('SsoServer::commons.toast')
    </div>

    @yield('bodyjs')

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/sso-server.js') }}"></script> --}}
</body>
</html>
