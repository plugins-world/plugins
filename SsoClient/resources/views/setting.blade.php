@extends('SsoClient::layouts.master')

@section('content')
<div class="container">
    <div class="card mx-auto mt-5" style="width: 75%;">
        <div class="card-body">
            <h1 class="card-title">Sso Client 设置</h1>

            <form class="row g-3 mt-5" action="{{ route('sso-client.setting') }}" method="post">
                @csrf

                <div class="mb-3 row">
                    <label for="sso_server_host" class="col-sm-2 col-form-label">Sso Server 地址</label>
                    <div class="col-sm-8">
                        <input type="text" name="sso_server_host" value="{{ old('sso_server_host', $configs['sso_server_host'] ?? '') }}" class="form-control" id="sso_server_host" placeholder="https://example.com/" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="sso_update_userinfo_service" class="col-sm-2 col-form-label">Sso 用户更新服务</label>
                    <div class="col-sm-8">
                        <!-- <input type="text" name="sso_update_userinfo_service" value="{{ old('sso_update_userinfo_service', $configs['sso_update_userinfo_service'] ?? '') }}" class="form-control" id="sso_update_userinfo_service" placeholder="请选择" required> -->
                        <select name="sso_update_userinfo_service" class="form-select" aria-label="Default select example">
                            <option>🚫禁用</option>

                            @foreach($plugins['sso_update_userinfo_service'] ?? [] as $plugin)
                            <option @if($configs['sso_update_userinfo_service'] == $plugin['unikey']) selected @endif value="{{ $plugin['unikey'] }}">{{ $plugin['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="col-sm-1 offset-sm-2 btn btn-primary mb-3">保存</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $(document).on('click', 'form button[type="submit"]', function (event) {
            $(this).prepend('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ');
            $(this).prop('disabled', true);

            $('form').submit();
        });

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
                    window.location.reload();
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
@endsection