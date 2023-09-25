@extends('EasySms::layouts.master')

@section('content')
<div class="container">
    <div class="card mx-auto mt-5" style="width: 75%;">
        <div class="card-body">
            <h1 class="card-title">{{ config('easy-sms.name') }} 设置</h1>

            <form class="row g-3 mt-5" action="{{ route('easy-sms.setting') }}" method="post">
                @csrf

                <div class="mb-3 row">
                    <label for="sms_default_gateway" class="col-sm-2 col-form-label">发信网关</label>
                    <div class="col-sm-6">
                        <select name="sms_default_gateway" class="form-select" aria-label="Default select example">
                            <option selected>qcloud</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="sign_name" class="col-sm-2 col-form-label">签名</label>
                    <div class="col-sm-6">
                        <input type="text" name="qcloud[sign_name]" value="{{ old('sign_name', $configs['qcloud']['sign_name'] ?? '') }}" class="form-control" id="sign_name" placeholder="请输入签名">
                    </div>
                    <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> <a href="https://console.cloud.tencent.com/smsv2/csms-sign" target="_blank">查看 "签名内容"</a> </div>
                </div>

                <div class="mb-3 row">
                    <label for="sdk_app_id" class="col-sm-2 col-form-label">SDK AppID</label>
                    <div class="col-sm-6">
                        <input type="text" name="qcloud[sdk_app_id]" value="{{ old('sdk_app_id', $configs['qcloud']['sdk_app_id'] ?? '') }}" class="form-control" id="sdk_app_id" placeholder="请输入 SDK AppID">
                    </div>
                    <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> <a href="https://console.cloud.tencent.com/smsv2/app-manage" target="_blank">查看 "SDKAppID"</a></div>
                </div>

                <div class="mb-3 row">
                    <label for="secret_id" class="col-sm-2 col-form-label">SecretID</label>
                    <div class="col-sm-6">
                        <input type="text" name="qcloud[secret_id]" value="{{ old('secret_id', $configs['qcloud']['secret_id'] ?? '') }}" class="form-control" id="secret_id" placeholder="请输入 SecretID">
                    </div>
                    <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> <a href="https://console.cloud.tencent.com/cam/capi" target="_blank">查看 "SecretId"</a></div>
                </div>

                <div class="mb-3 row">
                    <label for="secret_key" class="col-sm-2 col-form-label">SecretKey</label>
                    <div class="col-sm-6">
                        <input type="text" name="qcloud[secret_key]" value="{{ old('secret_key', $configs['qcloud']['secret_key'] ?? '') }}" class="form-control" id="secret_key" placeholder="请输入 SecretKey">
                    </div>
                    <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> <a href="https://console.cloud.tencent.com/cam/capi" target="_blank">查看 "SecretKey"</a></div>
                </div>

                <button type="submit" class="col-sm-1 offset-sm-2 btn btn-primary mb-3">保存</button>
            </form>
        </div>
    </div>
</div>
@endsection