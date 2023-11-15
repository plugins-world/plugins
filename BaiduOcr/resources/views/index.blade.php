@extends('BaiduOcr::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: {{ config('baidu-ocr.name') }}</h1>

        <p>
            This view is loaded from plugin: {{ config('baidu-ocr.name') }}
        </p>

        <a href="{{ route('baidu-ocr.setting') }}">Go to the {{ config('baidu-ocr.name') }} plugin settings page.</a>
    </div>
@endsection
