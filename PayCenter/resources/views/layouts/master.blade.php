<!doctype html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="PayCenter" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}">
        <title>{{ config('pay-center.name') }}</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.min.css">

        @stack('css')
    </head>

    <body>
        <header>
        {{-- @include('PayCenter::layouts.header') --}}
        </header>
        <main>
            @yield('content')
        </main>

        <footer>
            @include('PayCenter::layouts.footer')
        </footer>

        <!-- Tips -->
        <div class="fresns-tips">
            @include('PayCenter::layouts.tips')
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/iframe-resizer/js/iframeResizer.contentWindow.min.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    accept: 'application/json',
                    authorization: 'Bearer {{ csrf_token() }}',
                },
                crossDomain: true,
                xhrFields: {
                    withCredentials: true,
                },
            });

            /* Tooltips */
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // set timeout toast hide
            const setTimeoutToastHide = () => {
                $('.toast.show').each((k, v) => {
                    setTimeout(function () {
                        $(v).hide();
                    }, 1500);
                });
            };
            setTimeoutToastHide();

            // tips
            window.tips = function (message, code = 200) {
                let html = `<div aria-live="polite" aria-atomic="true" class="position-fixed top-50 start-50 translate-middle" style="z-index:99">
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                                <!-- <img src="/static/images/icon.png" width="20px" height="20px" class="rounded me-2" alt="{{ config('diancan.name') }}"> -->
                                <strong class="me-auto">{{ config('diancan.name') }}</strong>
                                <small>${code}</small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        <div class="toast-body">${message}</div>
                    </div>
                </div>`;
                $('div.fresns-tips').prepend(html);
                setTimeoutToastHide();
            };
        </script>

        @stack('script')
    </body>
</html>
