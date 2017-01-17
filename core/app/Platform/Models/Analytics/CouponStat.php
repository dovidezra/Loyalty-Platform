<?php
namespace Platform\Models\Analytics;

use Illuminate\Database\Eloquent\Model;

Class CouponStat extends Model
{

  protected $table = 'coupon_stats';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function getDates() {
    return array('created_at');
  }

  public function coupon() {
    return $this->hasOne('Platform\Models\Coupons\Coupon');
  }

  public function member() {
    return $this->hasOne('Platform\Models\Members\Member');
  }
}
