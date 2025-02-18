@extends('ClsLogger::layouts.master')

@section('content')
    <div class="container">
        <div class="card mx-auto mt-5" style="width: 75%;">
            <div class="card-body">
                <h1 class="card-title">{{ config('cls-logger.name') }} Settings</h1>
                <a href="{{ route('cls-logger.index') }}">Back to {{ config('cls-logger.name') }} plugin homepage.</a>

                <form class="row g-3 mt-5" action="{{ route('cls-logger.setting') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="example" class="col-sm-2 col-form-label">Example</label>
                        <div class="col-sm-8">
                            <input type="text" name="example" value="{{ old('example', $configs['example'] ?? '') }}" class="form-control" id="example" placeholder="Example">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="other_cls_logger_service" class="col-sm-2 col-form-label">Extension command word service cmdWordName</label>
                        <div class="col-sm-8">
                            <!-- <input type="text" name="other_cls_logger_service" value="{{ old('other_cls_logger_service', $configs['other_cls_logger_service'] ?? '') }}" class="form-control" id="other_cls_logger_service" placeholder="Please choose" required> -->
                            <select name="other_cls_logger_service" class="form-select" aria-label="Default select example">
                                <option>🚫 Deactivate</option>

                                @foreach($plugins['other_cls_logger_service'] ?? [] as $plugin)
                                <option @if($configs['other_cls_logger_service'] == $plugin['fskey']) selected @endif value="{{ $plugin['fskey'] }}">{{ $plugin['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="col-sm-1 offset-sm-2 btn btn-primary mb-3">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
