<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{config('admin.title')}} | {{ trans('admin.register') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/font-awesome/css/font-awesome.min.css") }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css") }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="{{ admin_base_path('/') }}"><b>{{config('admin.name')}}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">{{ trans('registration.reset_password') }}</p>

    @if (session('status'))
      <div class="alert alert-success">
        {{ trans('registration.reset_email_sent') }}
      </div>
    @else

    <form action="{{ route('admin.password.email') }}" method="post">

      <div class="form-group has-feedback {!! !$errors->has('email') ?: 'has-error' !!}">
        @if($errors->has('email'))
          <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{ $errors->first('email') }}</label><br>
        @endif

        <input type="text" class="form-control" placeholder="{{ trans('registration.email') }}" name="email" value="{{ old('email') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback {!! !$errors->has('captcha') ?: 'has-error' !!}">
        <div class="form-group has-feedback {!! !$errors->has('captcha') ?: 'has-error' !!}">

          @if($errors->has('captcha'))
            <label class="control-label" for="inputError" ><i class="fa fa-times-circle-o"></i>{{ $errors->first('captcha') }}</label></br>
          @endif
          <input class="form-control" style="display: inline;width: 210px;" placeholder="{{ trans('admin.captcha') }}" name="captcha">
          <img class="captcha" src="{{ captcha_src('admin') }}" onclick="this.src='{{ captcha_src('admin') }}'+'?'+Math.random()" style="margin-bottom:2px;height:33px;cursor: pointer;border: 1px solid #ccc;"  title="Click to refresh">
        </div>
      </div>

      <div class="row">

        <div class="col-xs-12">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('registration.send_password_reset_link') }}</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    @endif

    <hr>

    <div class="row text-center" style="margin-top: 1rem;">
      <a href="{{ route('admin.login') }}" class="text-center">{{ trans('registration.already_has_account') }}</a>
    </div>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

</body>
</html>
