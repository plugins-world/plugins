<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
    @include('SsoClient::commons.head', [
        'title' => 'Plugin SsoClient',
    ])

    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/sso-client.css') }}"> --}}
</head>

<body>
    <div class="position-relative">
        @yield('content')

        @include('SsoClient::commons.toast')
    </div>

    @yield('bodyjs')

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/sso-client.js') }}"></script> --}}
</body>
</html>
