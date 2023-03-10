<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plugin SsoClient</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/market-manager.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    @stack('headcss')

    <!-- topbar js: @see documention https://buunguyen.github.io/topbar/ -->
    <script src="https://buunguyen.github.io/topbar/topbar.min.js"></script>
    <!-- ansi_to_html: @see https://github.com/drudru/ansi_up -->
    <script src="https://cdn.jsdelivr.net/npm/ansi_up@5.1.0/ansi_up.min.js"></script>
    <!-- jquery: @see documention http://jquery.cuishifeng.cn/ -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <!-- jquery throttle and debounce: @see https://stackoverflow.com/questions/27787768/debounce-function-in-jquery -->
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js"></script>
    <!-- ajax global setting -->
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                xhrFields: {
                    withCredentials: true
                },
                crossDomain: true,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Animate requests globally
            $(document).ajaxStart(function() {
                topbar.show();
            });

            // Animate the end of requests globally
            $(document).ajaxComplete(function() {
                topbar.hide()
            });
        });
    </script>

    @stack('headjs')
</head>

<body>
    <div class="position-relative">
        @yield('content')

        <div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center w-100">
            <!-- Position it: -->
            <!-- - `.toast-container` for spacing between toasts -->
            <!-- - `top-0` & `end-0` to position the toasts in the upper right corner -->
            <!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->
            <div class="toast-container top-50 start-50 translate-middle p-3">
                <!-- Then put toasts within -->
                <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <img src="#" class="rounded me-2" alt="">
                        <strong class="me-auto">提示</strong>
                        <small class="text-muted">刚刚</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        操作成功！
                    </div>
                </div>
            <div>
        </div>
    </div>

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/sso-server.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
