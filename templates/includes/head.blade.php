<?php if ($state == 'live') { ?>
<link rel="manifest" href="{{ url('c/' . $hash_id . '/manifest.json') }}">

<meta name="apple-mobile-web-app-title" content="{!! $coupon->name !!}">
<link rel="apple-touch-icon-precomposed" href="{{ url($coupon->icon->url('ipad1x')) }}">
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="{{ url($coupon->icon->url('ipad1x')) }}">
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="{{ url($coupon->icon->url('iphone2x')) }}">
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ url($coupon->icon->url('ipad2x')) }}">
<?php } ?>

<?php
foreach($fonts as $font) {
  echo "<link href='" . $font . "' rel='stylesheet' type='text/css'>" . PHP_EOL;
}
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">