@extends('FileManage::layouts.base')

@section('content')
<div>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a href="https://plugins-world.cn" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                文件管理服务
            </a>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 link-secondary">首页</a></li>
                <li><a href="#price" class="nav-link px-2 link-dark">定价</a></li>
                <li><a href="#" class="nav-link px-2 link-dark">关于</a></li>
            </ul>

            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-outline-primary me-2" onclick="location.href = '/admin'">登录</button>
                <!-- <button type="button" class="btn btn-primary">注册</button> -->
            </div>
        </header>
    </div>
</div>

<div>
    <div class="container col-xxl-8 px-4 py-5">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-10 col-sm-8 col-lg-6">
                <!-- <img src="https://v5.bootcss.com/docs/examples/heroes/bootstrap-themes.png" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy"> -->
                <img src="https://mastercaihao.crm.cvm.iwnweb.com/static/img/main_pic.a49156b.png" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
            </div>
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold lh-1 mb-3">电脑文件，在线管理</h1>
                <p class="lead">客户关系管理的前沿创新</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <button type="button" class="btn btn-primary btn-lg px-4 me-md-2">获取演示站</button>
                    <button type="button" class="btn btn-outline-secondary btn-lg px-4">更新日志</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="container">
        <footer class="py-5">
            <div class="row" hidden>
                <div class="col-md-5 mb-3">
                    <form>
                        <h5>订阅更新</h5>
                        <p>我们的最新消息</p>
                        <div class="d-flex flex-column flex-sm-row w-100 gap-2">
                            <label for="newsletter1" class="visually-hidden">邮件</label>
                            <input id="newsletter1" type="text" class="form-control" placeholder="邮件">
                            <button class="btn btn-primary flex-shrink-0" type="button">订阅</button>
                        </div>
                    </form>
                </div>

                <div class="col-6 col-md-2 mb-3 offset-md-1">
                    <h5>Section</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Features</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Pricing</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">About</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2 mb-3">
                    <h5>Section</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Features</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Pricing</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">About</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2 mb-3">
                    <h5>Section</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Features</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Pricing</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">About</a></li>
                    </ul>
                </div>


            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-between pt-4 mt-4 border-top" style="height: 40px;">
                <p class="footer-verticel-middle">© 2023 牟勇 All rights reserved.</p>
                <ul class="list-unstyled d-flex footer-verticel-middle">
                    <li class="ms-3">
                        <!-- <div class="col-md-5 mb-3"> -->
                            <form class="d-flex gap-3" style="height: 40px;">
                                <h5 class="flex-shrink-0 footer-verticel-middle">邮箱</h5>
                                <p class="flex-shrink-0">我们的最新消息</p>
                                <div class="d-flex flex-column flex-sm-row w-100 gap-2">
                                    <label for="newsletter1" class="visually-hidden">邮件</label>
                                    <input id="newsletter1" type="text" class="form-control" placeholder="订阅更新">
                                    <button class="btn btn-primary flex-shrink-0" type="button">订阅</button>
                                </div>
                            </form>
                        <!-- </div> -->
                    </li>
                    <li class="ms-3"><a class="link-dark" href="#">插件世界</a></li>
                    <li class="ms-3"><a class="link-dark" href="#">文档站</a></li>
                    <!-- <li class="ms-3"><a class="link-dark" href="#"><svg class="bi" width="24" height="24">
                                <use xlink:href="#instagram"></use>
                            </svg></a></li>
                    <li class="ms-3"><a class="link-dark" href="#"><svg class="bi" width="24" height="24">
                                <use xlink:href="#facebook"></use>
                            </svg></a></li> -->
                </ul>
            </div>
        </footer>
    </div>
</div>

<style>
    .help {
        position: fixed;
        right: 0;
        top: 40%;
        background-image: linear-gradient(90deg, #316cf4, #316cf4);
        color: #fff;
    }

    .help img {
        width: 26px;
    }

    .help span {
        font-size: 12px;
    }
    .footer-verticel-middle {
        line-height: 40px;
    }
</style>
<div class="help d-flex">
    <div class="d-flex flex-column justify-content-center align-items-center p-3">
        <img src="https://www.5kcrm.com/public/static/index/images/home/qiyeweixin.png" alt="">
        <span class="mt-2">企业微信</span>

    </div>
</div>

<script>
    (function() {})()
</script>
@endsection