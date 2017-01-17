<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Platform') }}</title>

  <!-- Styles -->
  <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">
  <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- Scripts -->
  <script src="{{ url('assets/js/modernizr.min.js') }}"></script>

<style type="text/css">
html, body {
  height: 100%;
  width: 100%;
  text-align: center;
  display: table;
}

#box {
  display: table-cell;
  vertical-align: middle;
}

#logo {
  font-size: 5rem;
}

#logo i {
  font-size: 5rem;
  top: 6px;
  position: relative;
}
</style>

</head>
<body>

  <div id="box">
    <div id="logo">{!! config('app.icon') !!} <span>{{ config('app.name', 'Platform') }}</span></div>
    <p class="lead">&copy; {{ date('Y') }} | <a href="{{ url('login') }}">Create a Coupon</a></p>
  </div>

  <!-- Scripts -->
  <script src="<?php echo (env('APP_DEBUG', false)) ? url('assets/js/scripts.js'): url('assets/js/scripts.min.js'); ?>"></script>
</body>
</html>