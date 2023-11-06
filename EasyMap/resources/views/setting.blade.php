@extends('EasyMap::layouts.master')

@section('content')
    <div class="container">
        <div class="card mx-auto mt-5" style="width: 75%;">
            <div class="card-body">
                <h1 class="card-title">{{ config('easy-map.name') }} 设置</h1>

                <form class="row g-3 mt-5" action="{{ route('easy-map.setting') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="map_default_platform" class="col-sm-2 col-form-label">地图API平台</label>
                        <div class="col-sm-6">
                            <select name="map_default_platform" class="form-select" aria-label="Default select example">
                                <option value="amap" selected>高德地图</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="RequestUrl" class="col-sm-2 col-form-label">请求 Url</label>
                        <div class="col-sm-6">
                            <input type="text" name="amap[request_url]" value="{{ old('request_url', $amapConfig['request_url'] ?? 'https://restapi.amap.com') }}" class="form-control" id="request-url" placeholder="请输入请求URL">
                        </div>
                        <div class="col-sm-4 form-text"><i class="bi bi-info-circle"></i> 查看 <a href="https://lbs.amap.com/api/webservice/guide/api/georegeo" target="_blank">请求地址</a> </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="key" class="col-sm-2 col-form-label">Key</label>
                        <div class="col-sm-6">
                            <input type="text" name="amap[key]" value="{{ old('key', $amapConfig['key'] ?? '') }}" class="form-control" id="key" placeholder="请输入Key">
                        </div>
                        <div class="col-sm-4 form-text">
                            <i class="bi bi-info-circle"></i> 查看 <a href="https://lbs.amap.com/api/webservice/guide/create-project/get-key" target="_blank">"获取Key"</a>、<a href="https://console.amap.com/dev/key/app" target="_blank">"我的应用"</a>
                        </div>
                    </div>

                    <button type="submit" class="col-sm-1 offset-sm-2 btn btn-primary mb-3">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
