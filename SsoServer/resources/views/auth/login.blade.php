@extends('SsoServer::layouts.master')

@section('content')
<style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

    .b-example-divider {
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
    }

    .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
    }

    .bi {
        vertical-align: -.125em;
        fill: currentColor;
    }

    .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
    }

    .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }
</style>

<style>
    html,
    body {
        height: 100%;
    }

    body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
    }

    .form-signin {
        max-width: 330px;
        padding: 15px;
    }

    .form-signin .form-floating:focus-within {
        z-index: 2;
    }

    .form-signin input[type="username"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>
<div class="container">
    <main class="form-signin w-100 m-auto text-center">
        <form action="{{ \Plugins\SsoServer\Heplers\SsoHelper::getLoginUrl() }}" method="post">
            @csrf

            <input type="hidden" name="return_url" value="{{ old('return_url', \request('return_url')) }}">

            <img class="mb-4" src="https://v5.bootcss.com/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">请登录</h1>

            <div class="form-floating">
                <input type="text" name="username" value="{{ old('username') }}" class="form-control" id="floatingInput" placeholder="用户名">
                <label for="floatingInput">用户名</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="密码">
                <label for="floatingPassword">密码</label>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <!-- <input type="checkbox" value="remember-me"> 记住我 -->
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">登录</button>
            <a href="{{ \Plugins\SsoServer\Heplers\SsoHelper::getRegisterUrl() . '?return_url='. old('return_url', \request('return_url')) }}" class="w-100 btn btn-lg btn-light mt-1">注册</a>
        </form>
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </main>

    <a href="https://authorization.hwecs.iwnweb.com/system-authorization" target="_blank">授权中心</a>

</div>
@endsection