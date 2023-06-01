<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
    @include('Tenant::commons.head', [
        'title' => 'Tenant',
    ])

    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/tenant.css') }}"> --}}
</head>

<body>
    <div class="position-relative">
        @yield('content')

        @include('Tenant::commons.toast')
    </div>

    @yield('bodyjs')

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/tenant.js') }}"></script> --}}
</body>
</html>

