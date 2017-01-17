<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <div class="card-box" style="padding:0">
        <div class="row">
          <div class="col-sm-9">
            <h4 class="page-title m-0" style="padding:20px">{{ trans('global.welcome_name', ['name' => \Auth::user()->name]) }}</h4>
          </div>
          <div class="col-sm-3 text-right">
            <div class="input-group" style="margin:13px 13px 0 0">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control input-sm" id="grid_search" placeholder="{{ trans('global.search_') }}">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php if ($count == 0) { ?>

<div class="text-center" style="margin:3rem">
  <a href="#/coupon/new" class="btn btn-lg btn-primary" style="font-size:3rem">{{ trans('global.create_new_coupon') }}</a>
</div>

<?php } ?>
  <div class="row" id="grid">
<?php
foreach ($coupons as $coupon) {
  $sl = \Platform\Controllers\Core\Secure::array2string(array('coupon_id' => $coupon->id));
?>
    <div class="col-md-4 col-sm-6 item">
      <div class="text-center card-box">
        <div class="member-card">
          <div class=" m-b-10 center-block">
            <a href="#/coupon/edit/{{ $sl }}">
              <img src="{{ $coupon->icon->url('android4x') }}" alt="{{ $coupon->name }}" style="width:96px;height:96px;" class="img-rounded">
            </a>
          </div>
          <div>
            <h4 class="m-b-5 item-name">{{ $coupon->name }}</h4>
            <p class="text-muted m-b-0">{{ $coupon->number_of_times_redeemed }} &times; {{ trans('global.redeemed') }}</p>
          </div>
        </div>
      </div>
    </div>
<?php } ?>
  </div>
</div>
<script>
$('#grid').liveFilter('#grid_search', 'div.item', {
  filterChildSelector: '.item-name'
});
</script>