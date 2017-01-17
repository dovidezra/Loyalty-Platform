<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>{{ trans('global.redeem_coupon') }}</title>
<link href="{{ url('templates/assets/css/style.min.css') }}" rel="stylesheet">
<script>var app_root = "{{ url('/') }}";</script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-md-12 text-xs-center">
      <h1 class="m-y-3">{!! $coupon->name !!}</h1>
      
      <p class="lead">{!! $coupon->coupon_description_text !!}</p>
      <p>{{ $valid_from_until }}</p>
      <p>{{ $member->name }} &lt;{{ $member->email }}&gt;</p>

      <div class="alert alert-danger">{{ $invalid_msg }}</div>

    </div>
  </div>
</div>

<script src="{{ url('templates/assets/js/scripts.min.js') }}"></script> 

</body>
</html>