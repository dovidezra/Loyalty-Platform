<?php namespace Platform\Controllers\Analytics;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

class AnalyticsController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Analytics Controller
   |--------------------------------------------------------------------------
   |
   | Analytics related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Analytics
   */
  public function showAnalytics()
  {
    // Security link
    $sl = request()->get('sl', '');
    $sql_coupon = '1=1';
    $coupon_id = '';

    if ($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $coupon_id = $qs['coupon_id'];
      $sql_coupon = 'coupon_id = ' . $coupon_id;
      $sl = rawurlencode($sl);
    }

    // Range
    $date_start = request()->get('start', date('Y-m-d', strtotime(' - 30 day')));
    $date_end = request()->get('end', date('Y-m-d'));

    $from =  $date_start . ' 00:00:00';
    $to = $date_end . ' 23:59:59';

    /*
     |--------------------------------------------------------------------------
     | Coupons
     |--------------------------------------------------------------------------
     */
    $all_coupons = \Platform\Models\Coupons\Coupon::where('user_id', Core\Secure::userId())
      ->where('active', 1)
      ->select(['name', 'id'])
      ->orderBy('name', 'asc')
      ->get();

    // Parse data
    foreach($all_coupons as $coupon) {
      $coupon_sl = Core\Secure::array2string(['coupon_id' => $coupon->id]);
      $coupons[rawurlencode($coupon_sl)] = [
        'id' => $coupon->id,
        'name' => $coupon->name
      ];
    }

    /*
     |--------------------------------------------------------------------------
     | First date
     |--------------------------------------------------------------------------
     */
    $stats_found = false;
    $first_date = date('Y-m-d');

    $coupon_stats = \Platform\Models\Analytics\CouponStat::where('user_id', Core\Secure::userId())
      ->select(\DB::raw('DATE(created_at) as date'))
      ->whereRaw($sql_coupon)
      ->orderBy('date', 'asc')
      ->first();

    if (! empty($coupon_stats)) {
      $stats_found = true;
      $first_date = $coupon_stats->date;
    }

    /*
     |--------------------------------------------------------------------------
     | Parse views & redemptions
     |--------------------------------------------------------------------------
     */
    $coupon_stats_views = \Platform\Models\Analytics\CouponStat::where('user_id', Core\Secure::userId())
      ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(id) as views'), \DB::raw('redeemed'), \DB::raw('count(redeemed) as redeemed_count'))
      ->whereRaw($sql_coupon)
      ->where('created_at', '>=', $from)
      ->where('created_at', '<=', $to)
      ->groupBy([\DB::raw('DATE(created_at)'), \DB::raw('redeemed')])
      ->get()->toArray();

    // Create range
    $coupon_range = $this->getRange($date_start, $date_end);

    // Merge stats with range
    foreach($coupon_range as $date => $arr) {

      // Views
      $views = ($date < $first_date) ? NULL : 0;
      foreach($coupon_stats_views as $row) {
        if ($date == $row['date'] && $row['redeemed'] == 0) {
          $views = $row['views'];
          break 1;
        }
      }

      $arr = array_merge(['views' => $views], $arr);
      $coupon_range[$date] = $arr;

      // Redemptions
      $redemptions = ($date < $first_date) ? NULL : 0;
      foreach($coupon_stats_views as $row) {
        if ($date == $row['date'] && $row['redeemed'] == 1) {
          $redemptions = $row['redeemed_count'];
          break 1;
        }
      }

      $arr = array_merge(['redemptions' => $redemptions], $arr);
      $coupon_range[$date] = $arr;
    }

    /*
     |--------------------------------------------------------------------------
     | Parse location
     |--------------------------------------------------------------------------
     */
    $coupon_latlng = \Platform\Models\Analytics\CouponStat::where('user_id', Core\Secure::userId())
      ->select(\DB::raw('count(*) as views'), \DB::raw('lat'), \DB::raw('lng'), \DB::raw('city'))
      ->whereRaw($sql_coupon)
      ->where('created_at', '>=', $from)
      ->where('created_at', '<=', $to)
      ->groupBy([\DB::raw('lat'), \DB::raw('lng'), \DB::raw('city')])
      ->get()->toArray();

    /*
     |--------------------------------------------------------------------------
     | Parse members
     |--------------------------------------------------------------------------
     */
    $coupon_members_views = \Platform\Models\Analytics\CouponStat::where('coupon_stats.user_id', Core\Secure::userId())
			->leftJoin('members as m', 'coupon_stats.member_id', '=', 'm.id')
      ->select(\DB::raw('count(coupon_stats.id) as views'), \DB::raw('member_id'), \DB::raw('m.name'), \DB::raw('m.email'))
      ->whereRaw($sql_coupon)
      ->whereNotNull('m.email')
      ->where('coupon_stats.redeemed', 0)
      ->where('coupon_stats.created_at', '>=', $from)
      ->where('coupon_stats.created_at', '<=', $to)
      ->orderBy('views', 'desc')
      ->groupBy([\DB::raw('member_id'), \DB::raw('m.name'), \DB::raw('m.email')])
      ->get()
      ->toArray();

    $coupon_members_redemptions = \Platform\Models\Analytics\CouponStat::where('coupon_stats.user_id', Core\Secure::userId())
			->leftJoin('members as m', 'coupon_stats.member_id', '=', 'm.id')
      ->select(\DB::raw('count(coupon_stats.id) as redemptions'), \DB::raw('member_id'), \DB::raw('m.name'), \DB::raw('m.email'))
      ->whereRaw($sql_coupon)
      ->whereNotNull('m.email')
      ->where('coupon_stats.redeemed', 1)
      ->where('coupon_stats.created_at', '>=', $from)
      ->where('coupon_stats.created_at', '<=', $to)
      ->orderBy('redemptions', 'desc')
      ->groupBy([\DB::raw('member_id'), \DB::raw('m.name'), \DB::raw('m.email')])
      ->get()
      ->toArray();

    // Merge data
    $coupon_members = [];
    foreach($coupon_members_views as $i => $arr) {

      // Redemptions
      $redemptions = 0;
      foreach($coupon_members_redemptions as $row) {
        if ($row['member_id'] == $arr['member_id']) {
          $redemptions = $row['redemptions'];
          break 1;
        }
      }

      $arr = array_merge(['redemptions' => $redemptions], $arr);
      $coupon_members[$i] = $arr;
    }

    /*
     |--------------------------------------------------------------------------
     | Browsers
     |--------------------------------------------------------------------------
     */
    $coupon_browsers = \Platform\Models\Analytics\CouponStat::where('user_id', Core\Secure::userId())
      ->select(\DB::raw('count(*) as hits'), \DB::raw('client'))
      ->whereRaw($sql_coupon)
      ->where('created_at', '>=', $from)
      ->where('created_at', '<=', $to)
      ->orderBy('hits', 'desc')
      ->groupBy([\DB::raw('client')])
      ->get()
      ->toArray();

    /*
     |--------------------------------------------------------------------------
     | OS
     |--------------------------------------------------------------------------
     */
    $coupon_os = \Platform\Models\Analytics\CouponStat::where('user_id', Core\Secure::userId())
      ->select(\DB::raw('count(*) as hits'), \DB::raw('os'))
      ->whereRaw($sql_coupon)
      ->where('created_at', '>=', $from)
      ->where('created_at', '<=', $to)
      ->orderBy('hits', 'desc')
      ->groupBy([\DB::raw('os')])
      ->get()
      ->toArray();

    return view('platform.analytics.analytics', compact('sl', 'coupon_id', 'coupons', 'first_date', 'stats_found', 'date_start', 'date_end', 'coupon_range', 'coupon_latlng', 'coupon_members', 'coupon_browsers', 'coupon_os'));
  }

  /**
   * Add coupon stat
   */
  public static function addCouponStat($coupon = NULL, $member = NULL, $redeemed = false, $ip = NULL)
  {
    if ($ip == NULL) $ip = request()->ip();

    if ($member == NULL) {
      $member_id = (\Auth::guard('member')->check()) ? \Auth::guard('member')->user()->id : NULL;
    } else {
      $member_id = $member->id;
    }

    // Parse user agent
    $ua = Core\Piwik::getDevice();

    // Get location
    $position = \Location::get($ip);

    $country = ($position !== false && $position->countryCode != '') ? $position->countryCode : NULL;
    $city = ($position !== false && $position->cityName != '') ? $position->cityName : NULL;
    $lat = ($position !== false && $position->latitude != '') ? $position->latitude : NULL;
    $lng = ($position !== false && $position->longitude != '') ? $position->longitude : NULL;

    $coupon_stat = new \Platform\Models\Analytics\CouponStat;

    $coupon_stat->user_id = $coupon->user_id;
    $coupon_stat->coupon_id = $coupon->id;
    $coupon_stat->member_id = $member_id;
    $coupon_stat->redeemed = $redeemed;
    $coupon_stat->ip = $ip;
    $coupon_stat->os = $ua['os'];
    $coupon_stat->client = $ua['client'];
    $coupon_stat->device = $ua['device'];
    $coupon_stat->brand = $ua['brand'];
    $coupon_stat->model = $ua['model'];
    $coupon_stat->country = $country;
    $coupon_stat->city = $city;
    $coupon_stat->lat = $lat;
    $coupon_stat->lng = $lng;

    $coupon_stat->save();

    return $coupon_stat;
  }

  /**
   * Get date range
   * \Platform\Controllers\Analytics\AnalyticsController::getRange($date_start, $date_end);
   */
  public static function getRange($strDateFrom, $strDateTo)
  {
    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom)
    {
      $d = ['y' => (int) date('Y', $iDateFrom), 'm' => (int) date('n', $iDateFrom), 'd' => (int) date('j', $iDateFrom)];
      $aryRange[date('Y-m-d', $iDateFrom)] = $d; // first entry
      while ($iDateFrom < $iDateTo)
      {
        $iDateFrom +=86400; // add 24 hours
        $d = ['y' => (int) date('Y', $iDateFrom), 'm' => (int) date('n', $iDateFrom), 'd' => (int) date('j', $iDateFrom)];
        $aryRange[date('Y-m-d', $iDateFrom)] = $d;
        //array_push($aryRange, $d);
      }
    }
    return $aryRange;
  }
}