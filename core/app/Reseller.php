<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'resellers';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['domain'];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [];

  public function getDates() {
    return array('created_at', 'updated_at', 'expires');
  }

  public function users() {
    return $this->hasMany('\App\User');
  }
}
