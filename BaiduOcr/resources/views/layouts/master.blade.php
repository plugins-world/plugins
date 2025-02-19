<!doctype html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="BaiduOcr" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}">
        <title>BaiduOcr</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.min.css">

        <style>
            .required::before {
                content: "*";
                color: red;
                position: absolute;
                margin-left: -10px;
            }
        </style>
        @stack('css')
    </head>

    <body>
        <header>
            <!--@include('BaiduOcr::layouts.header')-->
        </header>

        <main>
            @yield('content')
        </main>

        <footer>
            @include('BaiduOcr::layouts.footer')
        </footer>

        <!-- Tips -->
        <div class="fresns-tips">
            @include('BaiduOcr::layouts.tips')
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/iframe-resizer/js/iframeResizer.contentWindow.min.js"></script>
        <script>
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
                let html = `<div aria-live="polite" aria-atomic="true" class="position-fixed top-50 start-50 translate-middle" style="z-index:99999">
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                                <img src="/static/images/icon.png" width="20px" height="20px" class="rounded me-2" alt="Fresns">
                                <strong class="me-auto">Fresns</strong>
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
