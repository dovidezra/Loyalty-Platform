<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Support\Facades\Gate;

class DashboardController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Dashboard Controller
   |--------------------------------------------------------------------------
   |
   | Dashboard related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Dashboard
   */

  public function showDashboard()
  {
    $coupons = \Platform\Models\Coupons\Coupon::where('user_id', Core\Secure::userId())->where('active', 1)->orderBy('name', 'asc')->get();

    $count = count($coupons);

    return view('platform.dashboard.dashboard', compact('count', 'coupons'));
  }
}