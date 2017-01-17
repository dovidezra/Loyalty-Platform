<?php namespace Platform\Controllers\Coupons;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

class EditController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Edit Controller
   |--------------------------------------------------------------------------
   |
   | Edit related logic
   |--------------------------------------------------------------------------
   */

  /**
   * New coupon
   */

  public function showNewCoupon() {

    $templates = $this->loadAllTemplates();

    return view('platform.coupons.coupon-new', compact('templates'));
  }

  /**
   * Coupons editor
   */

  public function showCouponEditor() {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $coupon = \Platform\Models\Coupons\Coupon::where('user_id', Core\Secure::userId())->where('id', $qs['coupon_id'])->first();

      if (! empty($coupon)) {
        $hash_id = Core\Secure::staticHash($coupon->id, true);
        $coupon_url = url('c/' . $hash_id);

        $coupon = \Platform\Controllers\Coupons\RenderController::mergeCouponDefaults($coupon, $coupon->template);

        return view('platform.coupons.coupon-edit', compact('coupon', 'coupon_url', 'sl'));
      }
    }
  }

  /**
   * New coupon
   */

  public function postNewCoupon() {
    $basename = request()->input('basename');

    $template_config = $this->loadTemplate($basename);
    $name = $this->getNextName($template_config['name_prefix']);
    $icon_default = url('templates/coupons/coupon01/icon.png'); // Icon in case no other icon is found
    $icon_template = public_path() . '/templates/coupons/' . $basename . '/icon.png';
    $icon = (\File::exists($icon_template)) ? url('templates/coupons/' . $basename . '/icon.png') : $icon_default;

    $coupon = new \Platform\Models\Coupons\Coupon;

    $coupon->user_id = Core\Secure::userId();
    $coupon->name = $name;
    $coupon->template = $basename;
    $coupon->icon = $icon;
    $coupon->redeem_code = mt_rand(1000, 9999);
    $coupon->valid_from_date = date('Y-m-d 00:00:00');
    $coupon->expiration_date = date('Y-m-d 23:59:59', strtotime(' + 3 months'));
    $coupon->timezone = \Auth::user()->timezone;
    $coupon->language = \Auth::user()->language;
    $coupon->redeemed_subject = trans('global.coupon_redeemed_subject');
    $coupon->redeemed_text = trans('global.coupon_redeemed_line1');

    foreach($template_config as $column => $value) {
      if (\Schema::hasColumn('coupons', $column)) $coupon->{$column} = $value;
    }

    $coupon->save();

    $sl = Core\Secure::array2string(['coupon_id' => $coupon->id]);

    return response()->json([
      'type' => 'success',
      'redir' => '#/coupon/edit/' . $sl
    ]);

  }

  public function getNextName($name_prefix, $count = 1) {
    $name = $name_prefix . ' ' . $count;
    $coupon = \Platform\Models\Coupons\Coupon::where('user_id', Core\Secure::userId())->where('name', $name)->first();

    if (! empty($coupon)) {
      return $this->getNextName($name_prefix, $count + 1);
    } else {
      return $name;
    }
  }

  public static function loadAllTemplates() {
    $templates_dir = public_path() . '/templates/coupons/';
    $templates = \File::directories($templates_dir);

    $return = [];

    foreach ($templates as $template) {
      $path = pathinfo($template);

      $return[] = [
        'dirname' => $path['dirname'],
        'basename' => $path['basename']
      ];
    }

    return $return;
  }

  public static function loadTemplate($template) {
    $config_file = public_path() . '/templates/coupons/' . $template . '/config.php';

    if(\File::exists($config_file)) {
      $config = \File::getRequire($config_file);
      return $config;
    } else {
      return false;
    }
  }

  /**
   * Update coupon
   */
  public function postCouponUpdate()
  {
    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);

      $coupon = \Platform\Models\Coupons\Coupon::where('user_id', Core\Secure::userId())->where('id', $qs['coupon_id'])->first();

      $input = array(
        'name' => request()->input('name'),
        'valid_from_date' => request()->input('valid_from_date'),
        'expiration_date' => request()->input('expiration_date'),
        'redeem_code' => request()->input('redeem_code'),
        'total_amount_of_coupons' => request()->input('total_amount_of_coupons', 0),
        'can_be_redeemed_more_than_once' => (bool) request()->input('can_be_redeemed_more_than_once', false),
        'navbar_text' => request()->input('navbar_text'),
        'navbar_text_font' => request()->input('navbar_text_font'),
        'coupon_title_font' => request()->input('coupon_title_font'),
        'navbar_text_size' => request()->input('navbar_text_size'),
        'coupon_title_text' => request()->input('coupon_title_text'),
        'coupon_title_font' => request()->input('coupon_title_font'),
        'coupon_title_size' => request()->input('coupon_title_size'),
        'button_text' => request()->input('button_text'),
        'button_text_font' => request()->input('button_text_font'),
        'button_text_size' => request()->input('button_text_size'),
        'coupon_description_text' => request()->input('coupon_description_text'),
        'coupon_description_font' => request()->input('coupon_description_font'),
        'coupon_description_size' => request()->input('coupon_description_size'),
        'coupon_description_color' => request()->input('coupon_description_color'),
        'navbar_background_color' => request()->input('navbar_background_color'),
        'navbar_text_color' => request()->input('navbar_text_color'),
        'coupon_title_color' => request()->input('coupon_title_color'),
        'button_background_color' => request()->input('button_background_color'),
        'button_background_color_hover' => request()->input('button_background_color_hover'),
        'button_text_color' => request()->input('button_text_color'),
        'qr_color' => request()->input('qr_color'),
        'border_color' => request()->input('border_color'),
        'header_image1' => request()->input('header_image1'),
        'background_image' => request()->input('background_image'),
        'background_image_repeat' => (bool) request()->input('background_image_repeat'),
        'coupon_background_image' => request()->input('coupon_background_image'),
        'coupon_background_image_repeat' => (bool) request()->input('coupon_background_image_repeat'),
        'coupon_background_color' => request()->input('coupon_background_color'),
        'background_color' => request()->input('background_color'),
        'redeemed_subject' => request()->input('redeemed_subject'),
        'redeemed_text' => request()->input('redeemed_text')
      );

      $rules = array(
        'name' => 'required',
        'valid_from_date' => 'date_format:Y-m-d',
        'expiration_date' => 'date_format:Y-m-d',
        'redeem_code' => 'required',
        'total_amount_of_coupons' => 'required|integer',
        'navbar_text' => 'required',
        'navbar_text_size' => 'required|integer',
        'coupon_title_text' => 'required',
        'coupon_title_size' => 'required|integer',
        'button_text' => 'required',
        'button_text_size' => 'required|integer'
      );

      $validator = \Validator::make($input, $rules);

      if($validator->fails())
      {
        $response = array(
          'type' => 'error', 
          'reset' => false, 
          'msg' => $validator->messages()->first()
        );
      }
      else
      {
        $coupon->name = $input['name'];
        $coupon->valid_from_date = $input['valid_from_date'];
        $coupon->expiration_date = $input['expiration_date'];
        $coupon->redeem_code = $input['redeem_code'];
        $coupon->total_amount_of_coupons = $input['total_amount_of_coupons'];
        $coupon->can_be_redeemed_more_than_once = $input['can_be_redeemed_more_than_once'];
        $coupon->navbar_text = $input['navbar_text'];
        $coupon->navbar_text_font = $input['navbar_text_font'];
        $coupon->coupon_title_font = $input['coupon_title_font'];
        $coupon->navbar_text_size = $input['navbar_text_size'];
        $coupon->coupon_title_text = $input['coupon_title_text'];
        $coupon->coupon_title_font = $input['coupon_title_font'];
        $coupon->coupon_title_size = $input['coupon_title_size'];
        $coupon->button_text = $input['button_text'];
        $coupon->button_text_font = $input['button_text_font'];
        $coupon->expiration_date = $input['expiration_date'];
        $coupon->button_text_size = $input['button_text_size'];
        $coupon->coupon_description_text = $input['coupon_description_text'];
        $coupon->coupon_description_font = $input['coupon_description_font'];
        $coupon->coupon_description_size = $input['coupon_description_size'];
        $coupon->coupon_description_color = $input['coupon_description_color'];
        $coupon->navbar_background_color = $input['navbar_background_color'];
        $coupon->navbar_text_color = $input['navbar_text_color'];
        $coupon->coupon_title_color = $input['coupon_title_color'];
        $coupon->button_background_color = $input['button_background_color'];
        $coupon->button_background_color_hover = $input['button_background_color_hover'];
        $coupon->button_text_color = $input['button_text_color'];
        $coupon->qr_color = $input['qr_color'];
        $coupon->border_color = $input['border_color'];
        $coupon->header_image1 = $input['header_image1'];
        $coupon->background_image = $input['background_image'];
        $coupon->background_image_repeat = $input['background_image_repeat'];
        $coupon->coupon_background_image = $input['coupon_background_image'];
        $coupon->coupon_background_image_repeat = $input['coupon_background_image_repeat'];
        $coupon->coupon_background_color = $input['coupon_background_color'];
        $coupon->background_color = $input['background_color'];
        $coupon->redeemed_subject = $input['redeemed_subject'];
        $coupon->redeemed_text = $input['redeemed_text'];

        if($coupon->save())
        {
          $response = array(
            'type' => 'success',
            'fn' => 'couponSaved'
          );
        }
        else
        {
          $response = array(
            'type' => 'error',
            'reset' => false, 
            'msg' => $coupon->errors()->first()
          );
        }
      }
      return response()->json($response);
    }
  }

  /**
   * Delete coupon
   */
  public function postCouponDelete()
  {
    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);
      $response = array('result' => 'success');

      $coupon = \Platform\Models\Coupons\Coupon::where('user_id', Core\Secure::userId())->where('id', $qs['coupon_id'])->first();

      if(! empty($coupon))
      {
        $coupon->forceDelete();
      }
    }
    return response()->json($response);
  }

  /**
   * Upload avatar
   */
  public function postAvatar() {
    $input = array(
      'file' => \Request::file('file'),
      'extension'  => strtolower(\Request::file('file')->getClientOriginalExtension())
    );

    $rules = array(
      'file' => 'mimes:jpeg,gif,png',
      'extension'  => 'required|in:jpg,jpeg,png,gif'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails()) {
       echo $validator->messages()->first();
       die();
    } else {
      $sl = request()->input('sl', NULL);

      $data = Core\Secure::string2array($sl);

      $coupon = \Platform\Models\Coupons\Coupon::where('user_id', Core\Secure::userId())->where('id', $data['coupon_id'])->first();
      $coupon->icon = $input['file'];
      $coupon->save();

      echo $coupon->icon->url('512px');
    }
  }

  /**
   * Delete avatar
   */
  public function postDeleteAvatar() {
    $sl = request()->input('sl', NULL);

    $data = Core\Secure::string2array($sl);

    $coupon = \Platform\Models\Coupons\Coupon::where('user_id', Core\Secure::userId())->where('id', $data['coupon_id'])->first();
    $coupon->icon = STAPLER_NULL;
    $coupon->save();

    return response()->json(['src' => $coupon->icon->url('512px')]);
  }
}