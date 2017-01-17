<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>{{ $coupon->navbar_text }}</title>
<link href="{{ url('templates/assets/css/style.min.css') }}" rel="stylesheet">
<script>var app_root = "{{ url('/') }}";</script>

@include('template::includes.head')

<style type="text/css">
<?php if ($state == 'edit' || $state == 'preview') { ?>
body, html {
  overflow: hidden;
}
<?php } ?>

body {
  padding: 1rem;
  background-color: {{ $coupon->background_color }} !important;
<?php if ($coupon->background_image != '') { ?>
  background-image: url("{{ $coupon->background_image }}");
<?php } ?>

<?php if (! $coupon->background_image_repeat) { ?>
  position: relative;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  background-attachment: fixed;
<?php } ?>
}

body nav.navbar {
  background-color: {{ $coupon->navbar_background_color }};
}

.drawer-hamburger-icon, .drawer-hamburger-icon:after, .drawer-hamburger-icon:before {
  background-color: {{ $coupon->navbar_text_color }};
}

.navbar .navbar-brand {
  color: {{ $coupon->navbar_text_color }};
  font-size: {{ $coupon->navbar_text_size }}px;
  font-family: {!! $coupon->navbar_text_font !!};
}

.btn-redeem {
  background-color: {{ $coupon->button_background_color }};
  border-color: {{ $coupon->button_background_color }};
  color: {{ $coupon->button_text_color }};
  font-size: {{ $coupon->button_text_size }}px;
}

.btn-redeem:hover {
  background-color: {{ $coupon->button_background_color_hover }};
  border-color: {{ $coupon->button_background_color_hover }};
}

.card {
  border-color: {{ $coupon->border_color }} !important;
  border-width: 0 !important;
  background-color: {{ $coupon->coupon_background_color }};
<?php if ($coupon->coupon_background_image != '') { ?>
  background-image: url("{{ $coupon->coupon_background_image }}");
<?php } ?>
<?php if (! $coupon->coupon_background_image_repeat) { ?>
  position: relative;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  background-attachment: fixed;
<?php } ?>
}

.coupon-border-top,
.coupon-border-bottom {
  border-color: {{ $coupon->border_color }};
}

.pass-title {
  color: {{ $coupon->coupon_title_color }};
  font-size: {{ $coupon->coupon_title_size }}px;
  font-family: {!! $coupon->coupon_title_font !!};
}

.pass-description {
  color: {{ $coupon->coupon_description_color }};
  font-size: {{ $coupon->coupon_description_size }}px;
  font-family: {!! $coupon->coupon_description_font !!};
}

.pass-valid-range {
  color: {{ $coupon->coupon_description_color }};
}
</style>

</head>
<body class="drawer drawer--right">

@include('template::includes.sidenav')

<main role="main">
  <div class="card coupon mdl-shadow--16dp" style="margin-bottom:0">
    <section>
      <nav class="navbar navbar-dark">
        <a class="navbar-brand" href="javascript:void(0);">{!! $coupon->navbar_text !!}</a>
      </nav>
    </section>
    <img class="card-img-top img-fluid" src="{{ $coupon->header_image1 }}" alt="{{ $coupon->navbar_text }}" style="margin:0 auto;">
    <div class="card-block text-xs-center">
      <div class="card-title pass-title">{!! $coupon->coupon_title_text !!}</div>
      <div class="card-text pass-description">{!! $coupon->coupon_description_text !!}</div>
    </div>
    <div class="card-block text-xs-center coupon-border-top">
<?php if(\Auth::guard('member')->check() || $state == 'preview') { ?>
<?php if($valid || $state == 'preview') { ?>
      <a href="{{ $redeem_url }}" target="_blank">
        <img src="{{ $barcode }}" alt="barcode" style="width:128px; height:128px">
      </a>
      <div class="m-t-1"><small class="pass-valid-range">{{ $valid_from_until }}</small></div>
<?php } else {?>
      <div class="pass-title"><h3>{!! $invalid_msg !!}</h3></div>
      <div class="m-t-1"><small class="pass-valid-range">{{ $valid_from_until }}</small></div>
<?php } ?>
<?php } else { ?>
      <div class="m-y-1">
        <a href="#" class="btn btn-block btn-xlg btn-green btn-redeem login">{!! $coupon->button_text !!}</a>
        <div class="m-t-1"><small class="pass-valid-range">{{ $valid_from_until }}</small></div>
      </div>
<?php } ?>
    </div>
  </div>
</main>
<script src="{{ url('templates/assets/js/scripts.min.js') }}"></script> 

@include('template::includes.bottom')

</body>
</html>