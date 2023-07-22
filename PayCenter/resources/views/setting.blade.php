@extends('PayCenter::layouts.master')

@section('content')
<div class="container">
    <div class="card mx-auto mt-5" style="width: 75%;">
        <div class="card-body">
            <!-- top -->
            <div class="row mb-2">
                <div class="col-8">
                    <h3 class="card-title">{{ config('pay-center.name') }} <span class="badge bg-secondary" style="font-size: .5rem;">{{ $version }}</span></h3>
                    <p class="text-secondary"></p>
                </div>
                <div class="col-4">
                    <div class="btn-group mt-2 mb-4 justify-content-lg-end px-1" role="group">
                        <button class="btn btn-outline-secondary" onclick="downloadCert(this)"><i class="bi bi-wechat"></i> 下载公钥证书证书</button>
                        <a class="btn btn-outline-secondary" href="https://github.com/plugins-world/plugins/tree/master/PayCenter" target="_blank" role="button"><i class="bi bi-github"></i> GitHub</a>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <div class="mb-3 mt-5">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="wechatPay-tab" data-bs-toggle="tab" data-bs-target="#wechatPay-tab-pane" type="button" role="tab" aria-controls="wechatPay-tab-pane" aria-selected="true">微信</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="aliPay-tab" data-bs-toggle="tab" data-bs-target="#aliPay-tab-pane" type="button" role="tab" aria-controls="aliPay-tab-pane" aria-selected="false">支付宝</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="uniPay-tab" data-bs-toggle="tab" data-bs-target="#uniPay-tab-pane" type="button" role="tab" aria-controls="uniPay-tab-pane" aria-selected="false">银联</button>
                    </li>
                    <li class="nav-item" role="presentation">
                    </li>
                </ul>
            </div>

            <form class="row g-3" action="{{ route('pay-center.setting') }}" method="post">
                @csrf

                <div class="tab-content" id="myTabContent">
                    <!-- 微信 -->
                    <div class="tab-pane fade show active" id="wechatPay-tab-pane" role="tabpanel" aria-labelledby="wechatPay-tab" tabindex="0">
                        <div class="row mb-2 d-none">
                            <label class="col-lg-2 col-form-label text-lg-end">微信支付配置信息</label>
                            <div class="col-lg-1">
                                <button class="btn btn-outline-primary btn-sm">新增</button>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 新增微信支付配置</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">mch_id</div>
                                    <input type="text" class="form-control" name="wechatPay[mch_id]" value="{{ $wechatPay['mch_id'] ?? '' }}" placeholder="商户号，服务商模式下为服务商商户号">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 必填-商户号，服务商模式下为服务商商户号，可在 <a href="https://pay.weixin.qq.com/" target="_blank"> https://pay.weixin.qq.com/</a> 账户中心->商户信息查看</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">mch_secret_key_v2</div>
                                    <input type="text" class="form-control" name="wechatPay[mch_secret_key_v2]" value="{{ $wechatPay['mch_secret_key_v2'] ?? '' }}" placeholder="v2商户私钥">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-v2商户私钥</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">mch_secret_key</div>
                                    <input type="text" class="form-control" name="wechatPay[mch_secret_key]" value="{{ $wechatPay['mch_secret_key'] ?? '' }}" placeholder="v3商户秘钥">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 必填-v3商户秘钥，即 API v3 密钥(32字节，形如md5值)，可在 账户中心->API安全 中设置</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-text">mch_secret_cert</div>

                                    @if(empty($wechatPay['mch_secret_cert']))
                                    <input type="file" class="form-control" onchange="uploadFile(this, 'mch_secret_cert')">
                                    <input type="hidden" class="form-control" name="wechatPay[mch_secret_cert]" value="">
                                    @else
                                    <input type="text" class="form-control" name="wechatPay[mch_secret_cert]" value="{{ $wechatPay['mch_secret_cert'] ?? '' }}" placeholder="商户私钥 字符串或路径">
                                    <div class="input-group-text file_status" style="display:block;">
                                        @if($wechatPay['mch_secret_cert'] ?? null) 已上传 @else 待上传 @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 必填-商户私钥 字符串或路径，即 API证书 PRIVATE KEY，可在 账户中心->API安全->申请API证书 里获得，文件名形如：apiclient_key.pem</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-text">mch_public_cert_path</div>
                                    
                                    @if(empty($wechatPay['mch_public_cert_path']))
                                    <input type="file" class="form-control" onchange="uploadFile(this, 'mch_public_cert_path')">
                                    <input type="hidden" class="form-control" name="wechatPay[mch_public_cert_path]" value="">
                                    @else
                                    <input type="text" class="form-control" name="wechatPay[mch_public_cert_path]" value="{{ $wechatPay['mch_public_cert_path'] ?? '' }}" placeholder="商户公钥证书路径">
                                    <div class="input-group-text file_status" style="display:block;">
                                        @if($wechatPay['mch_public_cert_path'] ?? null) 已上传 @else 待上传 @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 必填-商户公钥证书路径，即 API证书 CERTIFICATE，可在 账户中心->API安全->申请API证书 里获得，文件名形如：apiclient_cert.pem</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">notify_url</div>
                                    <input type="text" class="form-control" name="wechatPay[notify_url]" value="{{ $wechatPay['notify_url'] ?? route('pay-center.callback.wechatpay') }}" placeholder="微信回调url">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 必填-微信回调url，不能有参数，如?号，空格等，否则会无法正确回调</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">mp_app_id</div>
                                    <input type="text" class="form-control" name="wechatPay[mp_app_id]" value="{{ $wechatPay['mp_app_id'] ?? '' }}" placeholder="公众号的app_id">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-公众号的app_id，可在 <a href="https://mp.weixin.qq.com" target="_blank">https://mp.weixin.qq.com/</a> 设置与开发->基本配置->开发者ID(AppID) 查看</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">mini_app_id</div>
                                    <input type="text" class="form-control" name="wechatPay[mini_app_id]" value="{{ $wechatPay['mini_app_id'] ?? '' }}" placeholder="小程序的app_id">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-小程序的app_id</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">app_id</div>
                                    <input type="text" class="form-control" name="wechatPay[app_id]" value="{{ $wechatPay['app_id'] ?? '' }}" placeholder="app的app_id">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-app的app_id</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">combine_app_id</div>
                                    <input type="text" class="form-control" name="wechatPay[combine_app_id]" value="{{ $wechatPay['combine_app_id'] ?? '' }}" placeholder="合单的app_id">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-合单的app_id</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">combine_mch_id</div>
                                    <input type="text" class="form-control" name="wechatPay[combine_mch_id]" value="{{ $wechatPay['combine_mch_id'] ?? '' }}" placeholder="合单商户号">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-合单商户号</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">sub_mp_app_id</div>
                                    <input type="text" class="form-control" name="wechatPay[sub_mp_app_id]" value="{{ $wechatPay['sub_mp_app_id'] ?? '' }}" placeholder="服务商模式下，子公众号 的 app_id">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-服务商模式下，子公众号 的 app_id</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">sub_app_id</div>
                                    <input type="text" class="form-control" name="wechatPay[sub_app_id]" value="{{ $wechatPay['sub_app_id'] ?? '' }}" placeholder="服务商模式下，子 app 的 app_id">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-服务商模式下，子 app 的 app_id</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">sub_mini_app_id</div>
                                    <input type="text" class="form-control" name="wechatPay[sub_mini_app_id]" value="{{ $wechatPay['sub_mini_app_id'] ?? '' }}" placeholder="服务商模式下，子小程序 的 app_id">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-服务商模式下，子小程序 的 app_id</div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">sub_mch_id</div>
                                    <input type="text" class="form-control" name="wechatPay[sub_mch_id]" value="{{ $wechatPay['sub_mch_id'] ?? '' }}" placeholder="服务商模式下，子商户id">
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-服务商模式下，子商户id</div>
                        </div>

                        @if($wechatPay['wechat_public_cert_path'] ?? [])
                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                @foreach ($wechatPay['wechat_public_cert_path'] as $serialNo => $content)
                                <div class="input-group">
                                    <div class="input-group-text">wechat_public_cert_path</div>
                                    <input type="text" class="form-control" name="wechatPay[wechat_public_cert_path][{{$serialNo}}]" value="{{$content}}" placeholder="微信平台公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数">
                                    <button type="button" class="input-group-text" style="display:block;" onclick="removeCert(this)">
                                        删除
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-微信平台公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数</div>
                        </div>
                        @endif

                        <div class="row mb-2">
                            <label class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-text">mode</div>
                                    @php
                                    $mode = $wechatPay['mode'] ?? \Yansongda\Pay\Pay::MODE_NORMAL;
                                    @endphp
                                    <select class="form-select" name="wechatPay[mode]">
                                        <option value="{{ \Yansongda\Pay\Pay::MODE_NORMAL }}" {{ $mode == \Yansongda\Pay\Pay::MODE_NORMAL ? 'selected' : '' }}>MODE_NORMAL</option>
                                        <option value="{{ \Yansongda\Pay\Pay::MODE_SERVICE }}" {{ $mode == \Yansongda\Pay\Pay::MODE_SERVICE ? 'selected' : '' }}>MODE_SERVICE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE</div>
                        </div>
                    </div>

                    <!-- 支付宝 -->
                    <div class="tab-pane fade" id="aliPay-tab-pane" role="tabpanel" aria-labelledby="aliPay-tab" tabindex="0">
                    </div>

                    <!-- 银联 -->
                    <div class="tab-pane fade" id="uniPay-tab-pane" role="tabpanel" aria-labelledby="uniPay-tab" tabindex="0">
                    </div>
                </div>

                <!--保存按钮-->
                <div class="row mb-4">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-9">
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<script>
    function uploadFile(obj, field) {
        event.preventDefault();
        console.log(obj, field)

        const formdata = new FormData()
        formdata.append('file', obj.files[0])
        formdata.append('field', field)
        formdata.append('_token', "{{ csrf_token() }}")

        $.ajax({
            method: 'post',
            url: "{{ route('pay-center.wechatpay.upload-file') }}",
            data: formdata,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);

                if (response.err_code !== 200) {
                    window.tips(response.err_msg)
                } else {
                    $(`[name="wechatPay[${field}]"]`).val(response.data.filepath)
                }
            },
            error: function(error) {
                console.error(error);
                window.tips(error.responseJSON.message || error.responseJSON.err_msg || '未知错误')
            },
        });
    }

    function downloadCert(obj) {
        event.preventDefault();

        $.ajax({
            method: 'get',
            url: "{{ route('pay-center.wechatpay.download-public-cert') }}",
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);

                window.tips(response.err_msg)

                window.location.reload()
            },
            error: function(error) {
                console.error(error);
                window.tips(error.responseJSON.message || error.responseJSON.err_msg || '未知错误')
            },
        });
    }

    function removeCert(obj) {
        $(obj).parent().parent().parent().remove();
    }
</script>
@endpush