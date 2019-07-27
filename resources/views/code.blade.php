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
			<p class="description">小程序代码控制</p>
			

			<p>
				WeContr 小程序版本 v{{$master_version}}
			</p>

			<p>
				您当前小程序版本 v{{$current_version}} (当前发布版本 v{{$release_version?$release_version:'未发布'}}</a>) <br>
				<img style="width:180px;" src="{{$qrcode}}" />  <br>
				扫码体验(邀请非管理员体验请在微信小程序官方后台添加用户及权限)
			</p>
			
			@if($master_version != $current_version)

			@endif
			<a class="button" href="/wxoauth/commitCode?appid={{$app->id}}" >提交代码</a>
			<br>

			<a class="button" href="/wxoauth/submitAudit?appid={{$app->id}}" >提交审核</a>
			<br>

			<a class="button" href="/wxoauth/releaseCode?appid={{$app->id}}" >发布代码(全量)</a>
			<br>

			<p>
				如有疑问，请咨询客服QQ：121258121(在线时间9:00~23:00)
			</p>
		</section>
    </body>
</html>
