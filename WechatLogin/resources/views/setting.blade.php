@extends('WechatLogin::layouts.master')

@section('content')
    <div class="container">
        <div class="card mx-auto mt-5" style="width: 75%;">
            <div class="card-body">
                <!-- top -->
                <div class="row mb-2">
                    <div class="col-8">
                        <h3 class="card-title">WechatLogin <span class="badge bg-secondary" style="font-size: .5rem;">{{ $version }}</span></h3>
                        <p class="text-secondary">「微信登录」插件，支持网站、小程序、App 等各端的微信登录。</p>
                    </div>
                    <div class="col-4">
                        <div class="input-group mt-2 mb-4 justify-content-lg-end px-1" role="group">
                            <a class="btn btn-outline-secondary" href="https://github.com/plugins-world/plugins/tree/master/WechatLogin" target="_blank" role="button"><i class="bi bi-github"></i> GitHub</a>
                        </div>
                    </div>
                </div>

                <!-- Menu -->
                <div class="mb-3 mt-5">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="officialAccount-tab" data-bs-toggle="tab" data-bs-target="#officialAccount-tab-pane" type="button" role="tab" aria-controls="officialAccount-tab-pane" aria-selected="true">公众号</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="miniProgram-tab" data-bs-toggle="tab" data-bs-target="#miniProgram-tab-pane" type="button" role="tab" aria-controls="miniProgram-tab-pane" aria-selected="false">小程序</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="openPlatform-tab" data-bs-toggle="tab" data-bs-target="#openPlatform-tab-pane" type="button" role="tab" aria-controls="openPlatform-tab-pane" aria-selected="false">开放平台</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        </li>
                    </ul>
                </div>

                <form class="row g-3" action="{{ route('wechat-login.setting') }}" method="post">
                    @csrf

                    <div class="tab-content" id="myTabContent">
                        <!-- 公众号 -->
                        <div class="tab-pane fade show active" id="officialAccount-tab-pane" role="tabpanel" aria-labelledby="officialAccount-tab" tabindex="0">
                            <div class="alert alert-warning" role="alert">仅支持微信认证的服务号，订阅号没有网页授权权限。</div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end">公众号配置信息</label>
                                <div class="col-lg-1">
                                    <button class="btn btn-outline-primary btn-sm">新增</button>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 新增公众号配置</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text w-25">AppID</div>
                                        <input type="text" class="form-control" name="officialAccount[appId]" value="{{ $officialAccount['appId'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 开发者 ID 是公众号开发识别码</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text w-25">AppSecret</div>
                                        <input type="text" class="form-control" name="officialAccount[appSecret]" value="{{ $officialAccount['appSecret'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 开发者密码是校验公众号开发者身份的密码</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text w-25">网页授权域名</div>
                                        <input type="text" class="form-control bg-light" value="{{ str_replace(['http://', 'https://'], '', config('app.url')) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 用户在网页授权页同意授权给公众号后，微信会将授权数据传给授权域名的回调页面</div>
                            </div>
                        </div>

                        <!-- 小程序 -->
                        <div class="tab-pane fade" id="miniProgram-tab-pane" role="tabpanel" aria-labelledby="miniProgram-tab" tabindex="0">
                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end">小程序配置信息</label>
                                <div class="col-lg-1">
                                    <button class="btn btn-outline-primary btn-sm">新增</button>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 新增小程序配置</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text w-25">AppID</div>
                                        <input type="text" class="form-control" name="miniProgram[appId]" value="{{ $miniProgram['appId'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 小程序ID</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text w-25">AppSecret</div>
                                        <input type="text" class="form-control" name="miniProgram[appSecret]" value="{{ $miniProgram['appSecret'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 小程序密钥</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text">要打开的小程序版本</div>
                                        @php
                                            $envVersion = $miniProgram['envVersion'] ?? null;
                                        @endphp
                                        <select class="form-select" name="miniProgram[envVersion]">
                                            <option value="release" {{ $envVersion == 'release' ? 'selected' : '' }}>正式版</option>
                                            <option value="trial" {{ $envVersion == 'trial' ? 'selected' : '' }}>体验版</option>
                                            <option value="develop" {{ $envVersion == 'develop' ? 'selected' : '' }}>开发版</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 授权网页登录时生成的小程序码要打开哪个小程序版本</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text w-25">业务域名</div>
                                        <input type="text" class="form-control bg-light" value="{{ config('app.url') }}" >
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 配置为业务域名后，可使用 Fresns 扩展插件</div>
                            </div>
                        </div>

                        <!-- 开放平台 -->
                        <div class="tab-pane fade" id="openPlatform-tab-pane" role="tabpanel" aria-labelledby="openPlatform-tab" tabindex="0">
                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end">开放平台配置信息</label>
                                <div class="col-lg-1">
                                    <button class="btn btn-outline-primary btn-sm">新增</button>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 新增开放平台配置</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text">网站应用 AppID</div>
                                        <input type="text" class="form-control" name="openPlatform[website][appId]" value="{{ $openPlatform['website']['appId'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 微信开放平台网站应用 AppID</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text">网站应用 AppSecret</div>
                                        <input type="text" class="form-control" name="openPlatform[website][appSecret]" value="{{ $openPlatform['website']['appSecret'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 微信开放平台网站应用 AppSecret</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text">移动应用 AppID</div>
                                        <input type="text" class="form-control" name="openPlatform[mobile][appId]" value="{{ $openPlatform['mobile']['appId'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 微信开放平台移动应用 AppID</div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-lg-3 col-form-label text-lg-end"></label>
                                <div class="col-6">
                                    <div class="input-group">
                                        <div class="input-group-text">移动应用 AppSecret</div>
                                        <input type="text" class="form-control" name="openPlatform[mobile][appSecret]" value="{{ $openPlatform['mobile']['appSecret'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 微信开放平台移动应用 AppSecret</div>
                            </div>
                        </div>
                    </div>

                    <!--保存按钮-->
                    <div class="row mb-4">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-9">
                            <button type="submit" class="btn btn-primary" id="saveButton">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
