@extends('FileStorage::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card mx-auto mt-5" style="width: 75%;">
        <div class="card-body">
            <h1 class="card-title">文件存储设置页</h1>
            <!-- <a href="{{ route('file-storage.index') }}">返回到插件首页</a> -->

            <form class="row g-3 mt-5" action="{{ route('file-storage.setting') }}" method="post">
                @csrf

                @foreach($configs as $config)
                <div class="mb-3 row">
                    <label for="{{ $config['item_key'] }}" class="col-sm-2 col-form-label">{{ __('FileStorage::setting.'.$config['item_key']) }}</label>
                    <div class="col-sm-8">
                        @if($config['item_type'] == 'string')
                        <input type="text" name="{{ $config['item_key'] }}" value="{{ old($config['item_key'], $config['item_value'] ?? '') }}" class="form-control" id="{{ $config['item_key'] }}">
                        @endif

                        @if($config['item_type'] == 'boolean')
                        <input type="radio" class="btn-check" name="{{ $config['item_key'] }}" id="{{$config['item_key']}}-success-outlined" autocomplete="off" @if($config['item_value'] == true) checked @endif value="1">
                        <label class="btn btn-outline-success" for="{{$config['item_key']}}-success-outlined">是</label>

                        <input type="radio" class="btn-check" name="{{ $config['item_key'] }}" id="{{$config['item_key']}}-danger-outlined" autocomplete="off" @if($config['item_value'] == false) checked @endif value="0">
                        <label class="btn btn-outline-danger" for="{{$config['item_key']}}-danger-outlined">否</label>
                        @endif
                    </div>
                </div>
                @endforeach

                <button type="submit" class="col-sm-1 offset-sm-2 btn btn-primary mb-3">{{ __('FileStorage::setting.save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection