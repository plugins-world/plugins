@extends('BaiduOcr::layouts.master')

@section('content')
    <div class="container">
        <div class="card mx-auto mt-5" style="width: 75%;">
            <div class="card-body">
                <h1 class="card-title">{{ config('baidu-ocr.name') }} 设置</h1>

                <form class="row g-3 mt-5" action="{{ route('baidu-ocr.setting') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="RequestUrl" class="col-sm-2 col-form-label">请求 Url</label>
                        <div class="col-sm-6">
                            <input type="text" name="ocr_config[request_url]" value="{{ old('request_url', $config['request_url'] ?? 'https://aip.baidubce.com') }}" class="form-control" id="request-url" placeholder="请输入请求URL">
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看 <a href="https://ai.baidu.com/ai-doc/FACE/Xkxie8338#1%E8%8E%B7%E5%8F%96verify_token%E6%8E%A5%E5%8F%A3" target="_blank">请求地址</a> </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="APIKey" class="col-sm-2 col-form-label">API Key</label>
                        <div class="col-sm-6">
                            <input type="text" name="ocr_config[api_key]" value="{{ old('api_key', $config['api_key'] ?? '') }}" class="form-control" id="request-url" placeholder="请输入API Key">
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看 <a href="https://login.bce.baidu.com" target="_blank">获取 API Key</a> </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="SecretKey" class="col-sm-2 col-form-label">Secret Key</label>
                        <div class="col-sm-6">
                            <input type="text" name="ocr_config[secret_key]" value="{{ old('secret_key', $config['secret_key'] ?? '') }}" class="form-control" id="request-url" placeholder="请输入Secret Key">
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看 <a href="https://login.bce.baidu.com" target="_blank">获取 Secret Key</a> </div>
                    </div>

                    <button type="submit" class="col-sm-1 offset-sm-2 btn btn-primary mb-3">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
