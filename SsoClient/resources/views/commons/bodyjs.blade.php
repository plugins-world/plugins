<script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
@yield('bodyjs')

<script>
    $(function () {
        $(document).on('click', 'form button[type="submit"]', $.debounce(500, function(event) {
            $(this).prepend('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ');
            $(this).prop('disabled', true);

            $('form').submit();
        }));

        $('form').submit(function(event) {
            event.preventDefault();

            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: new FormData($(this)[0]),
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);

                    $('.toast').toast('show');
                    $('form button[type="submit"]').prop('disabled', false);
                    top.location.reload();
                },
                error: function(error) {
                    console.error(error);
                    $('.toast').find('.toast-body').html(error.responseJSON.message || error.responseJSON.err_msg || '未知错误');
                    $('.toast').toast('show');
                    $('form button[type="submit"] span').remove();
                    $('form button[type="submit"]').prop('disabled', false);
                },
            });
        });
    });
</script>
