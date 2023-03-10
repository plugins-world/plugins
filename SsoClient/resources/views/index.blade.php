@extends('SsoClient::layouts.master')

@section('content')
<div class="container">
    <h1>Plugin: SsoClient</h1>

    <p>
        This view is loaded from plugin: {!! config('sso-client.name') !!}
    </p>

    <a href="{{ route('sso-client.setting') }}">前往插件 SsoClient 设置页</a>
</div>
@endsection
