@extends('SsoServer::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card mx-auto mt-5" style="width: 75%;">
        <div class="card-body">
            <h1 class="card-title">Sso Server 设置</h1>

            <form class="row g-3 mt-5" action="{{ route('sso-server.setting') }}" method="post">
                @csrf

                <div class="mb-3 row">
                    <div class="col-sm-8 input-group mb-3">
                        <label for="ssoServerHostFormControlInput" class="col-sm-2 col-form-label">Sso Cookie 前缀</label>

                        <input type="text" name="sso_cookie_prefix" value="{{ old('sso_cookie_prefix', $configs['sso_cookie_prefix'] ?? '') }}" class="form-control" id="ssoServerHostFormControlInput" placeholder="可输入 cookie 前缀">

                        <span class="input-group-text">:sso</span>
                        <span class="input-group-text">:server</span>
                        <button class="btn btn-primary" type="submit">保存</button>
                    </div>
                    <div class="offset-sm-2 form-text">当前值：{{ $configs['sso_cookie_prefix'] ? $configs['sso_cookie_prefix'].':' : '' }}sso:server</div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection