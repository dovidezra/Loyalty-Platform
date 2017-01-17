<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('coupons', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

      $table->integer('published_id')->unsigned()->nullable();
      $table->foreign('published_id')->references('id')->on('coupons')->onDelete('cascade');
      $table->boolean('published')->default(false);

      // Members must be unique within namespace
      $table->string('namespace', 250)->nullable();

      $table->string('name', 250);
      $table->string('template', 64);
      $table->text('description')->nullable();
      $table->boolean('active')->default(true);

      $table->text('serial_number')->nullable();
      $table->boolean('auto_generate_pass_serial')->default(true);

      $table->timestamp('valid_from_date')->nullable();
      $table->timestamp('expiration_date')->nullable();
      $table->string('barcode_type', 20)->default('QRCODE');
      $table->string('redeem_code', 250)->nullable();
      $table->integer('number_of_times_redeemed')->default(0);
      $table->timestamp('last_redemption')->nullable();
      $table->integer('total_amount_of_coupons')->default(0);
      $table->integer('amount_of_coupons_per_user')->nullable();
      $table->integer('can_be_redeemed_more_than_once')->nullable();

      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');

      $table->boolean('header_image_full_width')->nullable();
      $table->string('header_image1')->nullable();
      $table->string('header_image2')->nullable();
      $table->string('header_image3')->nullable();
      $table->string('header_image4')->nullable();
      $table->string('header_image5')->nullable();
      $table->string('header_image6')->nullable();

      $table->string('qr_color', 20)->nullable();
      $table->string('border_color', 20)->nullable();

      $table->string('background_color', 20)->nullable();
      $table->string('background_image', 250)->nullable();
      $table->string('background_image_repeat')->nullable();

      $table->string('sidemenu_background_color', 20)->nullable();
      $table->string('sidemenu_background_image', 250)->nullable();
      $table->string('sidemenu_background_image_repeat')->nullable();
      $table->string('sidemenu_text_color', 20)->nullable();
      $table->string('sidemenu_text_font', 64)->nullable();
      $table->integer('sidemenu_text_size')->nullable();

      $table->string('coupon_background_color', 20)->nullable();
      $table->string('coupon_background_image', 250)->nullable();
      $table->string('coupon_background_image_repeat')->nullable();

      $table->string('navbar_text')->nullable();
      $table->string('navbar_link')->nullable();
      $table->string('navbar_background_color', 20)->nullable();
      $table->string('navbar_background_image', 250)->nullable();
      $table->string('navbar_background_image_repeat')->nullable();
      $table->string('navbar_text_color', 20)->nullable();
      $table->string('navbar_text_font', 64)->nullable();
      $table->integer('navbar_text_size')->nullable();

      $table->string('coupon_title_text')->nullable();
      $table->string('coupon_title_color', 20)->nullable();
      $table->string('coupon_title_font', 64)->nullable();
      $table->integer('coupon_title_size')->nullable();

      $table->mediumText('coupon_description_text')->nullable();
      $table->string('coupon_description_color', 20)->nullable();
      $table->string('coupon_description_font', 64)->nullable();
      $table->integer('coupon_description_size')->nullable();

      $table->string('button_text')->nullable();
      $table->string('button_icon')->nullable();
      $table->string('button_background_color', 20)->nullable();
      $table->string('button_background_color_hover', 20)->nullable();
      $table->string('button_text_color', 20)->nullable();
      $table->string('button_text_font', 64)->nullable();
      $table->integer('button_text_size')->nullable();

      $table->string('redeemed_subject')->nullable();
      $table->mediumText('redeemed_text')->nullable();

      // Icon
      $table->string('icon_file_name')->nullable();
      $table->integer('icon_file_size')->nullable();
      $table->string('icon_content_type')->nullable();
      $table->timestamp('icon_updated_at')->nullable();

      // Logo
      $table->string('logo_file_name')->nullable();
      $table->integer('logo_file_size')->nullable();
      $table->string('logo_content_type')->nullable();
      $table->timestamp('logo_updated_at')->nullable();

      $table->mediumText('settings')->nullable();
      $table->timestamps();
    });

    Schema::create('coupon_stats', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->integer('coupon_id')->unsigned();
      $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
      $table->integer('member_id')->unsigned()->nullable();
      $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

      $table->boolean('redeemed')->nullable();

      $table->string('ip', 40)->nullable();
      $table->string('os', 32)->nullable();
      $table->string('client', 32)->nullable();
      $table->string('device', 32)->nullable();
      $table->string('brand', 32)->nullable();
      $table->string('model', 32)->nullable();
      $table->char('country', 2)->nullable();
      $table->string('city', 32)->nullable();
      $table->decimal('lat', 10, 8)->nullable();
      $table->decimal('lng', 11, 8)->nullable();

      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });

    Schema::create('coupon_bundles', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('name', 250);
    });

    Schema::create('coupon_bundle_coupons', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('coupon_bundle_id')->unsigned();
      $table->foreign('coupon_bundle_id')->references('id')->on('coupon_bundles')->onDelete('cascade');
      $table->integer('coupon_id')->unsigned();
      $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('coupons');
    Schema::drop('coupon_stats');
    Schema::drop('coupon_bundles');
    Schema::drop('coupon_bundle_coupons');
  }
}
