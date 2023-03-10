<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />

@stack('headcss')

<link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- topbar js: @see documention https://buunguyen.github.io/topbar/ -->
<script src="https://cdn.jsdelivr.net/npm/topbar"></script>
<!-- ansi_to_html: @see https://github.com/drudru/ansi_up -->
<script src="https://cdn.jsdelivr.net/npm/ansi_up"></script>
<!-- jquery: @see documention http://jquery.cuishifeng.cn/ -->
<script src="https://cdn.jsdelivr.net/npm/jquery"></script>
<!-- jquery throttle and debounce: @see https://stackoverflow.com/questions/27787768/debounce-function-in-jquery -->
<script src="https://cdn.jsdelivr.net/npm/jquery-throttle-debounce"></script>
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
