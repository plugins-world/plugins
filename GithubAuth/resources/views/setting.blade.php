@extends('GithubAuth::layouts.master')

@section('content')
<div class="container">
    <div class="card mx-auto mt-5" style="width: 75%;">
        <div class="card-body">
            <h1 class="card-title">{{ config('github-auth.name') }}</h1>

            <form class="row g-3 mt-5" action="{{ route('github-auth.setting') }}" method="post">
                @csrf

                <div class="mb-3 row">
                    <label for="client_id" class="col-sm-2 col-form-label">client_id</label>
                    <div class="col-sm-8">
                        <input type="text" name="client_id" value="{{ $configs['client_id'] ?? '' }}" class="form-control" id="client_id">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="client_secret" class="col-sm-2 col-form-label">client_secret</label>
                    <div class="col-sm-8">
                        <input type="text" name="client_secret" value="{{ $configs['client_secret'] ?? '' }}" class="form-control" id="client_secret">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="redirect" class="col-sm-2 col-form-label">redirect</label>
                    <div class="col-sm-8">
                        <input type="text" name="redirect" value="{{ $configs['redirect'] ?? route('github-auth.auth.callback') }}" class="form-control" id="redirect">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="redirect" class="col-sm-2 col-form-label">是否开启代理</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="radio" class="btn-check" name="is_enable_proxy" id="product_type-success-outlined" autocomplete="off" @if(($configs['is_enable_proxy'] ?? null) == 1) checked @endif value="1">
                            <label class="btn btn-outline-success" for="product_type-success-outlined">开启</label>

                            <input type="radio" class="btn-check" name="is_enable_proxy" id="product_type-danger-outlined" autocomplete="off" @if(($configs['is_enable_proxy'] ?? null) == 0) checked @endif value="0">
                            <label class="btn btn-outline-danger" for="product_type-danger-outlined">关闭</label>
                        </div>
                    </div>
                </div>


                <div class="mb-3 row">
                    <label for="proxy_http" class="col-sm-2 col-form-label">proxy_http</label>
                    <div class="col-sm-8">
                        <input type="text" name="proxy_http" value="{{ $configs['proxy_http'] ?? $defaultProxy }}" class="form-control" id="proxy_http">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="proxy_https" class="col-sm-2 col-form-label">proxy_https</label>
                    <div class="col-sm-8">
                        <input type="text" name="proxy_https" value="{{ $configs['proxy_https'] ?? $defaultProxy }}" class="form-control" id="proxy_https">
                    </div>
                </div>

                <button type="submit" class="col-sm-1 offset-sm-2 btn btn-primary mb-3">保存</button>
            </form>
        </div>
    </div>
</div>
@endsection