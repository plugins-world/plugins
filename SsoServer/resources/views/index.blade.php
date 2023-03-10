@extends('SsoServer::layouts.master')

@section('content')
<div class="container">
    <h1>Plugin: SsoServer</h1>

    <p>
        This view is loaded from plugin: {!! config('sso-server.name') !!}
    </p>

    <a href="{{ route('sso-server.setting') }}">前往插件 SsoServer 设置页</a>
    <a href="https://authorization.hwecs.iwnweb.com/system-authorization">授权中心</a>
</div>
@endsection
