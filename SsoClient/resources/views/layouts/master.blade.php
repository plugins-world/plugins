<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
    <title>Plugin SsoClient</title>
    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/sso-client.css') }}"> --}}

    @include('SsoClient::commons.head')
</head>

<body>
    <div class="position-relative">
        @yield('content')

        @include('SsoClient::commons.toast')
    </div>

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/sso-client.js') }}"></script> --}}
    @include('SsoClient::commons.bodyjs')
</body>
</html>
