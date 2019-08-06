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
				授权前请先完成以下操作
				<ol>
				<li>已成功注册小程序</li>
				<li>已设置相关服务类目(企业设置“文娱 》 资讯”, 个人设置“教育 》 教育信息服务”)</li>
				</ol>
			</p>
			
			<p>
				操作流程说明
				<ol>
				<li>登录后台设置小程序相关信息<a target="_blank" href="https://readfollow.com/admin/auth/setting#tab-form-2">立即设置AppID和AppSecret</a></li>
				<li>点击“前往微信公众号平台授权”授权WeContr管理您的应用</li>
				<li>提交您的代码</li>
				<li>在后台添加基础数据</li>
				<li>扫描二维码体验小程序</li>
				<li>提交审核</li>
				<li>发布小程序</li>
				</ol>
			</p>
			<p>
				<a class="button" href="{{$url}}">前往微信公众号平台授权</a>
				<br>
				多谢您的使用
			</p>

			<a class="button" href="/admin" title="WeContr管理后台">进入后台</a>
			<p>
				如有疑问，请咨询客服QQ：121258121(在线时间14:00~22:00)
			</p>
		</section>
    </body>
</html>
