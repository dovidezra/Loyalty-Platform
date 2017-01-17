@extends('layouts.app')

@section('content') 
<header id="topnav">
  <div class="topbar-main">
    <div class="container"> 

      <div class="logo">
        <a href="#/" class="logo">{!! config('app.icon') !!} <span>{{ config('app.name', 'Platform') }}</span></a>
      </div>
      
      <div class="menu-extras">
        <ul class="nav navbar-nav navbar-right pull-right">
          <li class="dropdown"> <a href="" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown" aria-expanded="true"><img src="{{ \Auth::user()->getAvatar() }}" class="img-circle avatar"> </a>
            <ul class="dropdown-menu">
              <li><a href="#/profile"><i class="ti-user m-r-5"></i> {{ trans('global.profile') }}</a></li>
              <li><a href="{{ url('logout') }}"><i class="ti-power-off m-r-5"></i> {{ trans('global.logout') }}</a></li>
            </ul>
          </li>
        </ul>
        <div class="menu-item"> 
          <a class="navbar-toggle">
          <div class="lines">
            <span></span>
            <span></span>
            <span></span>
          </div>
          </a> 
        </div>
      </div>
    </div>
  </div>

  <div class="navbar-custom">
    <div class="container">
      <div id="navigation">
        <ul class="navigation-menu">
          <li class="has-submenu"><a href="#/"><i class="material-icons">&#xE871;</i> {{ trans('global.dashboard') }}</a></li>
          <li class="has-submenu"> <a href="javascript:void(0);"><i class="material-icons">&#xE89C;</i> {{ trans('global.new') }} &hellip;</a>
            <ul class="submenu">
              <li><a href="#/coupon/new">{{ trans('global.coupon') }}</a></li>
            </ul>
          </li>
          <li class="has-submenu"><a href="#/analytics"><i class="material-icons">&#xE6E1;</i> {{ trans('global.analytics') }}</a></li>
          <li class="has-submenu"><a href="#/members"><i class="material-icons">&#xE8F7;</i> {{ trans('global.members') }}</a></li>
          <li class="has-submenu"><a href="#/media"><i class="material-icons">&#xE8A7;</i> {{ trans('global.media') }}</a></li>
          <?php if (Gate::allows('admin-management')) { ?>
          <li class="has-submenu"> <a href="javascript:void(0);"><i class="material-icons">&#xE8B8;</i> {{ trans('global.admin') }}</a>
            <ul class="submenu">
              <li><a href="#/admin/users">{{ trans('global.users') }}</a></li>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</header>
<div class="wrapper">
  <section id="view">
  </section>
</div> 
@endsection 