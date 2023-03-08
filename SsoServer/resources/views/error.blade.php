@extends('SsoServer::layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">错误</h5>
            <h6 class="card-subtitle mb-2 text-muted">发生错误</h6>
            <p class="card-text">{{ $exception->getMessage() }}</p>
            <p class="card-text">{{ str_replace(base_path().'/', '', $exception->getFile()) }}:{{ $exception->getLine() }}</p>
            <!-- <a href="#" class="card-link">首页</a> -->
            <a href="{{ \Plugins\SsoServer\Heplers\SsoHelper::getIndexUrl(true) }}" class="card-link">前往首页</a>
        </div>
    </div>
</div>
@endsection