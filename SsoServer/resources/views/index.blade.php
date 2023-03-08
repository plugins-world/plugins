@extends('SsoServer::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from plugin: {!! config('sso-server.name') !!}
    </p>
    api_token: {{ \Plugins\SsoServer\Heplers\SsoCookieHelper::getServerCookieValue() ?? '未登录' }}

    <br>
    <a href="https://authorization.hwecs.iwnweb.com/system-authorization" target="_blank">授权中心</a>
@endsection
