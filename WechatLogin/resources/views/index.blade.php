@extends('WechatLogin::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: WechatLogin</h1>

        <p>
            This view is loaded from plugin: {!! config('wechat-login.name') !!}
        </p>

        <a href="{{ route('wechat-login.setting') }}">Go to the WechatLogin plugin settings page.</a>
    </div>
@endsection
