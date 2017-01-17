<?php namespace Platform\Controllers\Coupons;

use \Platform\Controllers\Core;
use Carbon\Carbon;

use App\Mail\CouponRedeemed;
use Illuminate\Support\Facades\Mail;

class RenderController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Render Controller
   |--------------------------------------------------------------------------
   |
   | Output render related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Render coupon
   */

  public function showCoupon($hash_id = NULL) {
    $valid = true;
    $valid_from_until = '';
    $invalid_msg = '';
    $template = request()->get('tpl', 'coupon01');
    $state = request()->get('state', 'live');

    if ($hash_id != NULL) {
      $coupon_id = Core\Secure::staticHashDecode($hash_id, true);
      $coupon = \Platform\Models\Coupons\Coupon::where('id', $coupon_id)->where('active', 1)->first();

      if (! empty($coupon)) {
        $user_id = $coupon->user_id;
        $template = $coupon->template;
        $redeem_url = (\Auth::guard('member')->check()) ? url('c/r/' . $hash_id . '/' . \Auth::guard('member')->user()->confirmation_code) : 'member not found';

        // Add stat
        if ($state == 'live') \Platform\Controllers\Analytics\AnalyticsController::addCouponStat($coupon);

        // Check if coupon can be redeemed
        if (\Auth::guard('member')->check()) {
          $validate = $this->validateCoupon($coupon, \Auth::guard('member')->user());
          $valid = $validate['valid'];
          $invalid_msg = $validate['error'];
          $valid_from_until = $validate['valid_from_until'];
        } else {
          $valid = false;
          $invalid_msg = trans('global.coupon_not_valid_anymore');

          $valid_from_until = trans('global.valid_from_until', [
            'from' => Carbon::parse($coupon->valid_from_date)->timezone($coupon->timezone)->format(trans('i18n.dateformat_expiration_dates')), 
            'until' => Carbon::parse($coupon->expiration_date)->timezone($coupon->timezone)->format(trans('i18n.dateformat_expiration_dates'))
          ]);
        }
      } else {
        die('Coupon not found');
      }
    } else {
      $coupon = NULL;
      $user_id = \Auth::user()->id;
      $redeem_url = 'Preview ' . $template;
      $valid_from_until = trans('global.valid_from_until', [
        'from' => date(trans('i18n.dateformat_expiration_dates')), 
        'until' => date(trans('i18n.dateformat_expiration_dates'), strtotime(' + 3 months'))
      ]);
    }

    $coupon = $this->mergeCouponDefaults($coupon, $template);

    // Parse and include fonts
    $font_columns = ['sidemenu_text_font', 'sidemenu_text_font', 'navbar_text_font', 'coupon_title_font', 'coupon_description_font', 'button_text_font'];
    $font_list = config('fonts.font_list');
    $fonts = [];

    foreach($font_columns as $font_column) {
      $font = (isset($coupon->{$font_column})) ? $coupon->{$font_column} : '';
      if ($font != '') {
        foreach($font_list as $name => $row) {
          if ($row['family'] == $font && $row['href'] != '') {
            $fonts[] = $row['href'];
            break 1;
          }
        }
      }
    }

    $fonts = array_unique($fonts);

    $sl_auth = Core\Secure::array2string(array('user_id' => $user_id, 'u' => 'c/' . $hash_id));

    if (! isset($coupon->qr_color)) $coupon->qr_color = '#000000';
    $barcode = \DNS2D::getBarcodePNGPath($redeem_url, "QRCODE", 10, 10, Core\Color::hex2rgb($coupon->qr_color));

    view()->addNamespace('template', public_path() . '/templates');

    return view('template::coupons.' . $template . '.index', compact('barcode', 'hash_id', 'fonts', 'state', 'coupon', 'sl_auth', 'redeem_url', 'valid', 'invalid_msg', 'valid_from_until'));
  }

  /**
   * Redeem coupon form
   */

  public function showRedeemCoupon($hash_id, $confirmation_code) {
    $valid = true;

    // Get coupon
    $coupon_id = Core\Secure::staticHashDecode($hash_id, true);
    $coupon = \Platform\Models\Coupons\Coupon::where('id', $coupon_id)->where('active', 1)->first();

    // Get member
    $member = \Platform\Models\Members\Member::where('confirmation_code', $confirmation_code)->first();

    if (! empty($coupon) && ! empty($member)) {
      $coupon = $this->mergeCouponDefaults($coupon, $coupon->template);
      $validate = $this->validateCoupon($coupon, $member);

      $valid = $validate['valid'];
      $invalid_msg = $validate['error'];
      $valid_from_until = $validate['valid_from_until'];

    } else {
      $invalid_msg = trans('global.coupon_or_member_not_found');
    }

    view()->addNamespace('template', public_path() . '/templates');

    if ($valid) {
      return view('template::admin.redeem-coupon', compact('hash_id', 'confirmation_code', 'coupon', 'member', 'valid_from_until'));
    } else {
      return view('template::admin.redeem-coupon-error', compact('error', 'coupon', 'member', 'valid_from_until', 'invalid_msg'));
    }
  }

  /**
   * Redeem coupon
   */

  public function postRedeemCoupon($hash_id, $confirmation_code) {
    $redeem_code = request()->get('redeem_code', '');

    // Get coupon
    $coupon_id = Core\Secure::staticHashDecode($hash_id, true);
    $coupon = \Platform\Models\Coupons\Coupon::where('id', $coupon_id)->where('redeem_code', $redeem_code)->where('active', 1)->first();

    // Get member
    $member = \Platform\Models\Members\Member::where('confirmation_code', $confirmation_code)->first();

    if (! empty($coupon) && ! empty($member)) {
      $coupon = $this->mergeCouponDefaults($coupon, $coupon->template);
      $validate = $this->validateCoupon($coupon, $member);

      $valid = $validate['valid'];
      $invalid_msg = $validate['error'];
      $valid_from_until = $validate['valid_from_until'];

      view()->addNamespace('template', public_path() . '/templates');

      if ($valid) {
        \Platform\Controllers\Analytics\AnalyticsController::addCouponStat($coupon, $member, true, $member->last_ip);

        $coupon->number_of_times_redeemed = $coupon->number_of_times_redeemed + 1;
        $coupon->last_redemption = Carbon::now();
        $coupon->save();

        // Send mail
        Mail::to($member)->send(new CouponRedeemed($coupon->redeemed_subject, $coupon->redeemed_text));

        return response()->json([
          'fn' => 'couponRedeemed'
        ]);

        return view('template::admin.coupon-redeemed', compact('hash_id', 'confirmation_code', 'coupon', 'member', 'valid_from_until'));
      } else {
        return response()->json([
          'type' => 'error', 
          'reset' => false, 
          'msg' => trans('global.invalid_msg')
        ]);
      }
    } else {
      return response()->json([
        'type' => 'error', 
        'reset' => false, 
        'msg' => trans('global.wrong_code')
      ]);
    }
  }

  /**
   * Check if coupon is valid
   */

  public static function validateCoupon($coupon, $member) {
    $valid = true;
    $error = NULL;

    $valid_from_date = Carbon::parse($coupon->valid_from_date)->timezone($coupon->timezone);
    $expiration_date = Carbon::parse($coupon->expiration_date)->timezone($coupon->timezone);

    $valid_from_until = trans('global.valid_from_until', [
      'from' => $valid_from_date->format(trans('i18n.dateformat_expiration_dates')), 
      'until' => $expiration_date->format(trans('i18n.dateformat_expiration_dates'))
    ]);

    if ($valid_from_date->format('Y-m-d 00:00:00') <= date('Y-m-d 00:00:00') && date('Y-m-d 23:59:59') <= $expiration_date->format('Y-m-d 23:59:59')) {

    } else {
      $valid = false;
      $error = trans('global.coupon_not_valid_anymore');
    }

    // Check if coupon has been redeemed
    if ($valid) {
      if ($coupon->number_of_times_redeemed < $coupon->total_amount_of_coupons || $coupon->total_amount_of_coupons == 0) {
        if ($coupon->can_be_redeemed_more_than_once == 0) {
          if (\Auth::guard('member')->check()) {
            $redemptions = \Platform\Models\Analytics\CouponStat::where('member_id', \Auth::guard('member')->user()->id)
            ->where('coupon_id', $coupon->id)
            ->where('redeemed', 1)
            ->get()->count();
  
            if ($redemptions > 0) {
              $valid = false;
              $error = trans('global.coupon_has_been_redeemed');
            }
  
          }
        }
      } else {
        $valid = false;
        $error = trans('global.coupon_has_been_redeemed');
      }
    }

    return [
      'valid' => $valid,
      'error' => $error,
      'valid_from_until' => $valid_from_until
    ];
  }

  /**
   * Merge coupon with config file
   *
   * Get template config and merge it with the coupon array
   */

  public static function mergeCouponDefaults($coupon, $template) {
    $template_path = 'coupons.' . $template . '.index';
    $config_file = public_path() . '/templates/coupons/' . $template . '/config.php';

    $config = (\File::exists($config_file)) ? \File::getRequire($config_file) : [];

    if ($coupon == NULL) return (object) $config;

   // $coupon_columns = \Schema::getColumnListing('coupons');

    $coupon_array = $coupon->toArray();
    $dates = $coupon->getDates();
    $date_only = (method_exists($coupon, 'getDateOnly')) ? $coupon->getDateOnly() : [];
    $timezone = $coupon->timezone;

    foreach ($coupon_array as $key => $value) {

      //if ($value == NULL && isset($config[$key])) $coupon->{$key} = $config[$key];

      // Adjust dates to timezone
      if (in_array($key, $dates)) {
        $coupon->{$key} = $coupon->{$key}->timezone($timezone)->format('Y-m-d H:i:s');
      }

      if (in_array($key, $date_only)) {
        $coupon->{$key} = Carbon::parse($coupon->{$key})->timezone($timezone)->format('Y-m-d');
      }
    }

    return $coupon;
  }

  /**
   * manifest.json
   */
  public function showManifest($hash_id = NULL) {
    if ($hash_id != NULL) {
      $coupon_id = Core\Secure::staticHashDecode($hash_id, true);
      $coupon = \Platform\Models\Coupons\Coupon::where('id', $coupon_id)->where('active', 1)->first();

      if (! empty($coupon)) {

        $extension = \File::extension($coupon->icon->url('ipad2x'));

        $manifest = '{
  "short_name": "' . str_replace('"', '\"', $coupon->name) . '",
  "name": "' . str_replace('"', '\"', $coupon->name) . '",
  "icons": [
    {
      "src": "' . url($coupon->icon->url('ipad2x')) . '",
      "sizes": "96x96",
      "type": "image/' . $extension . '"
    },
    {
      "src": "' . url($coupon->icon->url('ipad2x')) . '",
      "sizes": "144x144",
      "type": "image/' . $extension . '"
    },
    {
      "src": "' . url($coupon->icon->url('ipad2x')) . '",
      "sizes": "192x192",
      "type": "image/' . $extension . '"
    }
  ],
  "start_url": "' . url('c/' . $hash_id) . '?utm_source=web_app_manifest",
  "display": "standalone",
  "orientation": "portrait"
}';

        header("Content-Type: application/json;charset=utf-8");
        echo $manifest;

      }
    }
  }
}