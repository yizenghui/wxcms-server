<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>WxCMS</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    WxCMS
                </div>
                <div class="links m-b-md">
                    <a href="https://github.com/yizenghui/wxcms/issues/3">案例</a>
                    <a href="https://github.com/yizenghui/wxcms/archive/master.zip">下载</a>
                    <a href="https://github.com/yizenghui/wxcms/issues/4">安装</a>
                    <!-- <a href="https://github.com/yizenghui/wxcms/issues/5">开发</a> -->
                    <a href="/admin">后台</a>
                </div>
                <div>
                <strong>WxCMS</strong>是一款适合微信自媒体内容运营的小程序，小程序基于Wepy+ColorUI打造。
                <br>用户可以使用平台接口完成<a href="/admin">数据内容管理服务</a>(限免)。
                <br>
                <a href="https://github.com/yizenghui/wxcms">小程序在Github开源</a>(后端在开源的路上)</div>
            </div>
        </div>
    </body>
</html>
