@extends('BaiduFaceOcr::layouts.master')

@section('content')
    <div class="container">
        <div class="card mx-auto mt-5" style="width: 75%;">
            <div class="card-body">
                <h1 class="card-title">{{ config('baidu-face-ocr.name') }} 设置</h1>

                <form class="row g-3 mt-5" action="{{ route('baidu-face-ocr.setting') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="RequestUrl" class="col-sm-2 col-form-label">请求 Url</label>
                        <div class="col-sm-6">
                            <input type="text" name="face_ocr_config[request_url]" value="{{ old('request_url', $config['request_url'] ?? 'https://aip.baidubce.com') }}" class="form-control" id="request-url" placeholder="请输入请求URL">
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看 <a href="https://ai.baidu.com/ai-doc/FACE/Xkxie8338#1%E8%8E%B7%E5%8F%96verify_token%E6%8E%A5%E5%8F%A3" target="_blank">请求地址</a> </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="APIKey" class="col-sm-2 col-form-label">API Key</label>
                        <div class="col-sm-6">
                            <input type="text" name="face_ocr_config[api_key]" value="{{ old('api_key', $config['api_key'] ?? '') }}" class="form-control" id="api-key" placeholder="请输入API Key">
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看 <a href="https://console.bce.baidu.com/ai/#/ai/face/app/list" target="_blank">获取 API Key</a> </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="SecretKey" class="col-sm-2 col-form-label">Secret Key</label>
                        <div class="col-sm-6">
                            <input type="text" name="face_ocr_config[secret_key]" value="{{ old('secret_key', $config['secret_key'] ?? '') }}" class="form-control" id="secret-key" placeholder="请输入Secret Key">
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看 <a href="https://console.bce.baidu.com/ai/#/ai/face/app/list" target="_blank">获取 Secret Key</a> </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="id" class="col-sm-2 col-form-label">方案 ID</label>
                        <div class="col-sm-6">
                            <input type="text" name="face_ocr_plan[id]" value="{{ old('id', $plan['id'] ?? '') }}" class="form-control" id="id" placeholder="请输入方案 ID">
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看详情 <a href="https://console.bce.baidu.com/ai/#/ai/face/identify/projectManage" target="_blank">获取 方案 ID</a> </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-sm-2 col-form-label">方案名</label>
                        <div class="col-sm-6">
                            <input type="text" name="face_ocr_plan[name]" value="{{ old('name', $plan['name'] ?? '') }}" class="form-control" id="name" placeholder="请输入方案方案名">
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看详情 <a href="https://console.bce.baidu.com/ai/#/ai/face/identify/projectManage" target="_blank">获取 方案名</a> </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="type" class="col-sm-2 col-form-label">方案类型</label>
                        <div class="col-sm-6">
                            <select name="face_ocr_plan[type]" class="form-select" aria-label="Default select example">
                                <option @if($plan['type'] == 'H5') selected @endif>H5</option>
                                <option @if($plan['type'] == 'APP') selected @endif>APP</option>
                            </select>
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看 <a href="https://login.bce.baidu.com" target="_blank">获取 方案类型</a> </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="type" class="col-sm-2 col-form-label">认证方式</label>
                        <div class="col-sm-6">
                            <select name="face_ocr_plan[identification_method]" class="form-select" aria-label="Default select example">
                                <option @if($plan['identification_method'] == 'user_input') selected @endif value="user_input">用户输入</option>
                                <option @if($plan['identification_method'] == 'user_upload') selected @endif value="user_upload">用户上传</option>
                                <option @if($plan['identification_method'] == 'business_input') selected @endif value="business_input">业务传入</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="col-sm-1 offset-sm-2 btn btn-primary mb-3">保存</button>
                </form>
            </div>
        </div>
    </div>
@endsection
