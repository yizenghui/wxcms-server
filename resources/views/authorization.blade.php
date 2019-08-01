<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WeContr自媒体内容管理系统 - WxCMS</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.min.css">
        
    </head>
    <body>
		<section class="container" style="padding-top:50px;" >
			<h3 class="title">WeContr</h3>
			<p class="description">请求获取授权，为您代发布小程序代码。</p>
			
			<p>
				授权前请先完成以后设置
				<ol>
				<li>已成功注册小程序</li>
				<li>已设置服务类目</li>
				</ol>
			</p>
			
			<p>
				操作流程说明
				<ol>
				<li>登录后台设置小程序相关信息<a target="_blank" href="https://readfollow.com/admin/auth/setting#tab-form-2">立即设置</a></li>
				<li>已设置服务类目</li>
				</ol>
			</p>
			<p>
				<a class="button" href="{{$url}}">前往微信公众号平台授权</a>
				<br>
				多谢您的使用
			</p>

			<p>
				如有疑问，请咨询客服QQ：121258121(在线时间9:00~23:00)
			</p>
		</section>
    </body>
</html>
