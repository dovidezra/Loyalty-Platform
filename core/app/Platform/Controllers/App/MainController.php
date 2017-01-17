<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;

class MainController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Main Controller
   |--------------------------------------------------------------------------
   |
   | Main back end related logic
   |
   |--------------------------------------------------------------------------
   */

  /**
   * Main layout
   */

  public function main()
  {
    return view('platform.main', [
      'languages' => Core\Localization::getLanguagesArray()
    ]);
  }

  /**
   * Login
   */

  public function login()
  {
    return view('app.auth.login');
  }

}