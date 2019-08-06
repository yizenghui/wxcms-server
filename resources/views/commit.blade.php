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
			<p class="description">小程序代码提交成功，请扫描下方二维码进行体验。</p>
			


			<p> 体验小程序 <br> <img style="width:180px;" src="{{$qrcode}}" /> </p>
			<br>
			<p>请根据自身情况检查设置类目(WeContr暂不支持帐号设置，所以无法为您自动创建类目)</p>
			@if(empty($categories))
			
				<p>检查到您的小程序还未添加类目</p>
				<p>
					企业小程序请添加类目  文娱 > 资讯 <br>
					个人小程序请添加类目  教育 > 教育信息服务 <br>
				</p>
			@endif
			<p class="description">系统会优先选择文娱资讯类目进行提交，其次选择第一个类目提交（请根据您的使用情况选择合适的类目，WeContr不承担因类目内容不符合造成的任何损失）</p>
			<a class="button" href="/wxoauth/submitAudit?appid={{$app->tid}}" >提交审核</a>

			<p class="description">提交审核后，由微信官方团队进行审核，审核通过后可进行发布。</p>

			<a class="button" href="/admin" title="WeContr管理后台">进入后台</a>
			<p>
				如有疑问，请咨询客服QQ：121258121(在线时间14:00~22:00)
			</p>
		</section>
    </body>
</html>
