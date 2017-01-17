<?php
namespace Platform\Models\Coupons;

use Illuminate\Database\Eloquent\Model;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

Class Coupon extends Model implements StaplerableInterface
{
  use EloquentTrait;

  protected $table = 'coupons';

  public function getDates() {
    return array('created_at', 'updated_at');
  }

  public function getDateOnly() {
    return array('valid_from_date', 'expiration_date');
  }

  public function __construct(array $attributes = array()) {

      $this->hasAttachedFile('icon', [
          'styles' => [
              '1x' => '29x29#',
              '2x' => '58x58#',
              'iphone1x' => '60x60#',
              'iphone2x' => '120x120#',
              'ipad1x' => '76x76#',
              'ipad2x' => '152x152#',
              'android0-75x' => '36x36#',
              'android1x' => '48x48#',
              'android1-5x' => '72x72#',
              'android2x' => '96x96#',
              'android3x' => '144x144#',
              'android4x' => '192x192#',
              '512px' => '512x512#'
          ]
      ]);

      $this->hasAttachedFile('logo', [
          'styles' => [
              '1x' => '160x50',
              '2x' => '320x100',
              '3x' => '640x200'
          ]
      ]);

      parent::__construct($attributes);
  }

  public function user() {
    return $this->hasOne('App\User');
  }
}
