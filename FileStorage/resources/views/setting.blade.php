@extends('FileStorage::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card mx-auto mt-5" style="width: 75%;">
        <div class="card-body">
            <h1 class="card-title">文件存储设置页</h1>

            <form class="mt-5" action="{{ route('file-storage.setting') }}" method="post">
                @csrf

                <div class="row">
                    <label class="col-lg-3 col-form-label text-lg-end"></label>
                    <div class="col-6">
                        <div class="input-group">
                            <div class="input-group-text w-25 required">存储驱动</div>
                            <select class="form-select" name="file_storage_driver">
                                <option value="public" @if($file_storage_driver == 'public') selected @endif>本地存储 public</option>
                                <option value="local" @if($file_storage_driver == 'local') selected @endif>本地存储 private</option>
                                <option value="cos" @if($file_storage_driver == 'cos') selected @endif>腾讯云 COS</option>
                                <option value="oss" @if($file_storage_driver == 'oss') selected @endif>阿里云 OSS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 form-text"><i class="bi bi-info-circle"></i> 文件使用的存储驱动</div>
                </div>

                <div class="collapse @if($file_storage_driver == 'cos') show @endif" id="cosConfig">
                    @foreach($cosConfigs as $config)
                    <div class="row mt-2">
                        <label class="col-lg-3 col-form-label text-lg-end"></label>
                        <div class="col-6">
                            <div class="input-group">
                                <div class="input-group">
                                    <label for="cos-{{ $config['item_key'] }}" class="input-group-text @if($config['item_key'] != 'is_use_center_config') w-25 @endif">{{ __('FileStorage::setting.'.$config['item_key']) }}</label>
                                    @if($config['item_type'] == 'string')
                                    <input type="text" name="{{ $config['item_key'] }}" value="{{ old($config['item_key'], $config['item_value'] ?? '') }}" class="form-control" id="cos-{{ $config['item_key'] }}">
                                    @endif

                                    @if($config['item_type'] == 'boolean')
                                    <input type="radio" class="btn-check" name="cos-{{ $config['item_key'] }}" id="cos-{{ $config['item_key'] }}-success-outlined" autocomplete="off" @if($config['item_value']==true) checked @endif value="1">
                                    <label class="btn btn-outline-success" for="cos-{{$config['item_key']}}-success-outlined">是</label>

                                    <input type="radio" class="btn-check" name="cos-{{ $config['item_key'] }}" id="cos-{{ $config['item_key'] }}-danger-outlined" autocomplete="off" @if($config['item_value']==false) checked @endif value="0">
                                    <label class="btn btn-outline-danger" for="cos-{{$config['item_key']}}-danger-outlined">否</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="collapse @if($file_storage_driver == 'oss') show @endif" id="ossConfig">
                    @foreach($ossConfigs as $config)
                        <div class="row mt-2">
                            <label class="col-lg-3 col-form-label text-lg-end"></label>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group">
                                        <label for="oss-{{ $config['item_key'] }}" class="input-group-text @if($config['item_key'] != 'is_use_center_config') w-25 @endif">{{ __('FileStorage::setting.'.$config['item_key']) }}</label>
                                        @if($config['item_type'] == 'string')
                                            <input type="text" name="{{ $config['item_key'] }}" value="{{ old($config['item_key'], $config['item_value'] ?? '') }}" class="form-control" id="oss-{{ $config['item_key'] }}">
                                        @endif

                                        @if($config['item_type'] == 'boolean')
                                            <input type="radio" class="btn-check" name="oss-{{ $config['item_key'] }}" id="oss-{{ $config['item_key'] }}-success-outlined" autocomplete="off" @if($config['item_value']==true) checked @endif value="1">
                                            <label class="btn btn-outline-success" for="oss-{{$config['item_key']}}-success-outlined">是</label>

                                            <input type="radio" class="btn-check" name="oss-{{ $config['item_key'] }}" id="oss-{{ $config['item_key'] }}-danger-outlined" autocomplete="off" @if($config['item_value']==false) checked @endif value="0">
                                            <label class="btn btn-outline-danger" for="oss-{{$config['item_key']}}-danger-outlined">否</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 form-text"><i class="bi bi-info-circle"></i>{{ __('FileStorage::setting.'.$config['item_key']. '_msg') }}</div>
                        </div>

                    @endforeach
                </div>

                <div class="row mt-2">
                    <label class="col-lg-3 col-form-label text-lg-end"></label>
                    <div class="col-6">
                        <div class="input-group">
                            <div class="w-25"></div>
                            <button type="submit" class="btn btn-primary rounded-2">{{ __('FileStorage::setting.save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(function() {
        $(document).on('change', '[name="file_storage_driver"]', function() {
            const value = $(this).val();

            switch (value) {
                case 'local':
                    $('#cosConfig').collapse('hide');
                    $('#ossConfig').collapse('hide');
                    break;
                case 'cos':
                    $('#cosConfig').collapse('show');
                    $('#ossConfig').collapse('hide');
                    break;
                case 'oss':
                    $('#cosConfig').collapse('hide');
                    $('#ossConfig').collapse('show');
                    break;
            };
        });
    });
</script>
@endpush
