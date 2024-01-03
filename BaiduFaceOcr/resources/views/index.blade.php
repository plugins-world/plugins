@extends('BaiduFaceOcr::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: {{ config('baidu-face-ocr.name') }}</h1>

        <p>
            This view is loaded from plugin: {{ config('baidu-face-ocr.name') }}
        </p>

        <a href="{{ route('baidu-face-ocr.setting') }}">Go to the {{ config('baidu-face-ocr.name') }} plugin settings page.</a>
    </div>
@endsection
