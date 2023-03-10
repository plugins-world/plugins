@extends('DemoTest::layouts.master')

@section('content')
<div class="container">
    <h1>Plugin: DemoTest</h1>

    <p>
        This view is loaded from plugin: {!! config('demo-test.name') !!}
    </p>

    <a href="{{ route('demo-test.setting') }}">前往插件 DemoTest 设置页</a>
</div>
@endsection
