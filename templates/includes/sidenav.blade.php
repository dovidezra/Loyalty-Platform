<header role="banner">
  <button type="button" class="drawer-toggle drawer-hamburger"> <span class="sr-only">toggle navigation</span> <span class="drawer-hamburger-icon"></span> </button>
  <nav class="drawer-nav" role="navigation">
    <ul class="drawer-menu text-xs-center m-t-2">
<?php if(\Auth::guard('member')->check()) { ?>
      <li><a class="drawer-brand content" href="{{ url('member/profile') }}" style="text-align:center"><img src="{{ \Auth::guard('member')->user()->getAvatar() }}" class="img-circle m-b-1" alt="{{ \Auth::guard('member')->user()->name }}" style="width:110px;height:110px;"></a></li>

      <li><a class="drawer-menu-item content" href="{{ url('member/profile') }}">{{ trans('global.my_settings') }}</a></li>
      <li><a class="drawer-menu-item logout" href="javascript:void(0);">{{ trans('global.logout') }}</a></li>
<?php } else { ?>
<?php if ($state != 'preview') { ?>
      <li><a class="drawer-brand login" href="javascript:void(0);" style="text-align:center"><img src="{{ $coupon->icon->url('512px') }}" class="img-rounded m-b-1" alt="{{ $coupon->name }}" style="width:110px;height:110px"></a></li>
<?php } ?>
      <li><a class="drawer-menu-item content" href="{{ url('member/register') }}">{{ trans('global.register') }}</a></li>
      <li><a class="drawer-menu-item login" href="javascript:void(0);">{{ trans('global.login') }}</a></li>
<?php } ?>
    </ul>
  </nav>
</header>