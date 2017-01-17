<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('resellers', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name', 250);
      $table->string('domain', 250);
      $table->string('default_language', 5)->default('en');
      $table->string('default_timezone', 32)->default('UTC');
      $table->string('mail_from_address', 64)->nullable();
      $table->string('mail_from_name', 64)->nullable();
      $table->string('mail_host', 150)->nullable();
      $table->string('mail_port', 5)->nullable();
      $table->string('mail_encryption', 5)->nullable();
      $table->text('mail_username')->nullable();
      $table->text('mail_password')->nullable();
      $table->string('page_title', 250)->nullable();
      $table->string('favicon', 250)->nullable();
      $table->string('logo', 250)->nullable();
      $table->string('contact_name', 64)->nullable();
      $table->string('contact_mail', 64)->nullable();
      $table->boolean('active')->default(true);
      $table->mediumText('settings')->nullable();
      $table->dateTime('expires')->nullable();
      $table->timestamps();
    });

    Schema::create('subscriptions', function ($table) {
      $table->increments('id');
      $table->integer('reseller_id')->unsigned()->nullable();
      $table->foreign('reseller_id')->references('id')->on('resellers');
      $table->integer('user_id');
      $table->string('name');

      // Stripe
      $table->string('stripe_id')->nullable();
      $table->string('stripe_plan')->nullable();

      // Braintree
      $table->string('braintree_id')->nullable();
      $table->string('braintree_plan')->nullable();

      $table->integer('quantity')->nullable();
      $table->mediumText('settings')->nullable();
      $table->timestamp('trial_ends_at')->nullable();
      $table->timestamp('ends_at')->nullable();
      $table->timestamps();
    });

    Schema::create('users', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('reseller_id')->unsigned()->nullable();
      $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('set null');
      $table->boolean('reseller')->default(false);
      $table->integer('parent_id')->unsigned()->nullable();
      $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
      $table->integer('subscription_id')->unsigned()->nullable();
      $table->foreign('subscription_id')->references('id')->on('subscriptions');
      $table->string('role', 20)->default('user');
      $table->string('name', 64);
      $table->string('email');
      $table->string('password', 60)->nullable();
      $table->string('api_token', 60)->nullable()->unique();
      $table->boolean('active')->default(true);
      $table->boolean('confirmed')->default(false);
      $table->string('confirmation_code')->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->integer('logins')->default(0)->unsigned();
      $table->string('last_ip')->nullable();
      $table->dateTime('last_login')->nullable();
      $table->mediumText('settings')->nullable();

      // Avatar
      $table->string('avatar_file_name')->nullable();
      $table->integer('avatar_file_size')->nullable();
      $table->string('avatar_content_type')->nullable();
      $table->timestamp('avatar_updated_at')->nullable();

      // Stripe
      $table->string('stripe_id')->nullable();

      // Braintree
      $table->string('braintree_id')->nullable();
      $table->string('paypal_email')->nullable();

      // General
      $table->string('card_brand')->nullable();
      $table->string('card_last_four')->nullable();
      $table->timestamp('trial_ends_at')->nullable();

      $table->rememberToken();
      $table->timestamps();
    });

    Schema::create('password_resets', function (Blueprint $table) {
      $table->string('email')->index();
      $table->string('token')->index();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('password_resets');
    Schema::drop('users');
    Schema::drop('subscriptions');
    Schema::drop('resellers');
  }
}
