<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{config('admin.title')}} | {{ trans('admin.login') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/font-awesome/css/font-awesome.min.css") }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css") }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/iCheck/square/blue.css") }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page" @if(config('admin.login_background_image'))style="background: url({{config('admin.login_background_image')}}) no-repeat;background-size: cover;"@endif>
<div class="login-box">
  <div class="login-logo">
    <a href="{{ admin_base_path('/') }}"><b>{{config('admin.name')}}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">{{ trans('admin.login') }}</p>

    <form action="{{ admin_base_path('auth/login') }}" method="post">
      <div class="form-group has-feedback {!! !$errors->has('email') ?: 'has-error' !!}">

        @if($errors->has('email'))
          <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{ $errors->first('email') }}</label><br>
        @endif

        <input type="text" class="form-control" placeholder="{{ trans('registration.email') }}" name="email" value="{{ old('email') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback {!! !$errors->has('password') ?: 'has-error' !!}">

        @if($errors->has('password'))
          <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{ $errors->first('password') }}</label><br>
        @endif

        <input type="password" class="form-control" placeholder="{{ trans('registration.password') }}" name="password" value="{{ old('password') }}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
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
        <div class="col-xs-8">
          @if(config('admin.auth.remember'))
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember" value="1" {{ (!old('email') || old('remember')) ? 'checked' : '' }}>
              {{ trans('admin.remember_me') }}
            </label>
          </div>
          @endif
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('admin.login') }}</button>
        </div>
        <!-- /.col -->
      </div>

    </form>

    <hr>

    <div class="row" style="margin-top: 1rem;">
      <div class="col-md-6 text-center"><a href="{{ route('admin.password.request') }}"> {{ trans('registration.forgot_password') }}?</a></div>
      <div class="col-md-6 text-center"><a href="{{ route('admin.register') }}" class="text-center"> {{ trans('registration.register') }}</a></div>
    </div>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js")}} "></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<!-- iCheck -->
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
