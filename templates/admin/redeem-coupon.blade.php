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
    <div class="card mdl-shadow--16dp" style="padding:2rem; margin-top:2rem">
      <h1 class="m-b-2">{{ trans('global.redeem') }} {!! $coupon->name !!}</h1>
      <p class="lead">{!! $coupon->coupon_description_text !!}</p>
      <p>{{ $valid_from_until }}</p>
      <p>{{ $member->name }} &lt;{{ $member->email }}&gt;</p>
      <form class="form form-horizontal flat-form ajax" role="form" method="POST" action="{{ url('c/r/' . $hash_id . '/' . $confirmation_code) }}">
        {{ csrf_field() }}
        <div class="form-group m-y-2">
          <div class="input-group"> <span class="input-group-addon"><i class="material-icons" style="position:relative;top:3px">&#xE0DA;</i></span>
            <input type="password" class="form-control form-control-lg" placeholder="{{ trans('global.redeem_code') }}" name="redeem_code" required>
          </div>
        </div>
        <div class="form-group">
          <button class="btn btn-primary btn-xlg" type="submit">{{ trans('global.redeem_coupon') }}</button>
        </div>
      </form>
    </div>
    </div>
  </div>
</div>
<script src="{{ url('templates/assets/js/scripts.min.js') }}"></script>
<script>
onPartialLoaded();

function couponRedeemed() {
  swal({
    type: 'success',
    title: "{{ trans('global.coupon_successfully_redeemed') }}"
  }, function() {
    document.location.reload();
  });
}
</script>
</body>
</html>