@extends('DemoTest::layouts.master')

@section('content')
<div class="container">
    <div class="card mx-auto mt-5" style="width: 75%;">
        <div class="card-body">
            <h1 class="card-title">DemoTest 设置</h1>
            <a href="{{ route('demo-test.index') }}">回到插件 DemoTest 首页</a>

            <form class="row g-3 mt-5" action="{{ route('demo-test.setting') }}" method="post">
                @csrf

                <div class="mb-3 row">
                    <label for="example" class="col-sm-2 col-form-label">Example</label>
                    <div class="col-sm-8">
                        <input type="text" name="example" value="{{ old('example', $configs['example'] ?? '') }}" class="form-control" id="example" placeholder="Example">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="other_demo_test_service" class="col-sm-2 col-form-label">扩展命令字服务 cmdWordName</label>
                    <div class="col-sm-8">
                        <!-- <input type="text" name="other_demo_test_service" value="{{ old('other_demo_test_service', $configs['other_demo_test_service'] ?? '') }}" class="form-control" id="other_demo_test_service" placeholder="请选择" required> -->
                        <select name="other_demo_test_service" class="form-select" aria-label="Default select example">
                            <option>🚫禁用</option>

                            @foreach($plugins['other_demo_test_service'] ?? [] as $plugin)
                            <option @if($configs['other_demo_test_service'] == $plugin['unikey']) selected @endif value="{{ $plugin['unikey'] }}">{{ $plugin['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="col-sm-1 offset-sm-2 btn btn-primary mb-3">保存</button>
            </form>
        </div>
    </div>
</div>
@endsection