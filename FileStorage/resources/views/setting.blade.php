@extends('FileStorage::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card mx-auto mt-5" style="width: 75%;">
        <div class="card-body">
            <h1 class="card-title">文件存储设置页</h1>

            <form class="row g-3 mt-5" action="{{ route('file-storage.setting') }}" method="post">
                @csrf

                <div class="row">
                    <label class="col-lg-3 col-form-label text-lg-end"></label>
                    <div class="col-6">
                        <div class="input-group">
                            <div class="input-group-text w-25">存储驱动</div>
                            <select class="form-select" name="file_storage_driver">
                                <option value="local">Local</option>
                                <option value="cos">腾讯云 COS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 form-text pt-1"><i class="bi bi-info-circle"></i> 文件使用的存储驱动</div>
                </div>

                <div class="collapse" id="cosConfig">
                    @foreach($configs as $config)
                    <div class="row mt-2">
                        <label class="col-lg-3 col-form-label text-lg-end"></label>
                        <div class="col-6">
                            <div class="input-group">
                                <div class="input-group">
                                    <label for="{{ $config['item_key'] }}" class="input-group-text w-25">{{ __('FileStorage::setting.'.$config['item_key']) }}</label>
                                    @if($config['item_type'] == 'string')
                                    <input type="text" name="{{ $config['item_key'] }}" value="{{ old($config['item_key'], $config['item_value'] ?? '') }}" class="form-control" id="{{ $config['item_key'] }}">
                                    @endif

                                    @if($config['item_type'] == 'boolean')
                                    <input type="radio" class="btn-check" name="{{ $config['item_key'] }}" id="{{$config['item_key']}}-success-outlined" autocomplete="off" @if($config['item_value']==true) checked @endif value="1">
                                    <label class="btn btn-outline-success" for="{{$config['item_key']}}-success-outlined">是</label>

                                    <input type="radio" class="btn-check" name="{{ $config['item_key'] }}" id="{{$config['item_key']}}-danger-outlined" autocomplete="off" @if($config['item_value']==false) checked @endif value="0">
                                    <label class="btn btn-outline-danger" for="{{$config['item_key']}}-danger-outlined">否</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row mt-2">
                    <label class="col-lg-3 col-form-label text-lg-end"></label>
                    <div class="col-6">
                        <div class="input-group">
                            <div class="w-25"></div>
                            <button type="submit" class="btn btn-primary mb-3 rounded-2">{{ __('FileStorage::setting.save') }}</button>
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
                    break;
                case 'cos':
                    $('#cosConfig').collapse('show');
                    break;
            };
        });
    });
</script>
@endpush