<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
    <title>Plugin SsoServer</title>
    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/sso-server.css') }}"> --}}

    @include('SsoServer::commons.head')
</head>

<body>
    <div class="position-relative">
        @yield('content')

        @include('SsoServer::commons.toast')
    </div>

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/sso-server.js') }}"></script> --}}
    @include('SsoServer::commons.bodyjs')
</body>
</html>
