<?php
namespace Platform\Controllers\Core;

class Reseller extends \App\Http\Controllers\Controller {
  
  /*
  |--------------------------------------------------------------------------
  | Reseller Controller
  |--------------------------------------------------------------------------
  |
  | Reseller related logic
  |--------------------------------------------------------------------------
  */
  
  /**
   * \Platform\Controllers\Core\Reseller::get();
   * Returns current reseller data
   */
  public static function get() {
    if (\Auth::check()) {
      $reseller = \App\Reseller::find(\Auth::user()->reseller_id);
    } else {
      $reseller = \App\Reseller::where('domain', \Request::getHost())->first();
      
      if (! $reseller) {
        $reseller = \App\Reseller::where('domain', '*')->first();
      }
    }
    
    if ($reseller) {
      $reseller->url = ($reseller->domain == '*') ? url('/') : 'http://' . $reseller->domain;
      
      if ($reseller->favicon == NULL) $reseller->favicon = url('assets/images/branding/favicon.png');
      if ($reseller->page_title == NULL) $reseller->page_title = $reseller->name;
    } else {
      $reseller = new \stdClass;
      $reseller->url = url('/');
      $reseller->active = false;
    }
    
    return $reseller;
  }
}