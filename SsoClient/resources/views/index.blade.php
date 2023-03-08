@extends('SsoClient::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from plugin: {!! config('sso-client.name') !!}
    </p>
@endsection
