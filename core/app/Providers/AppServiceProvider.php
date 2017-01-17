<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      $url_current = str_replace('https://', '', str_replace('http://', '', url()->current()));
      $reset_url = request()->server('HTTP_HOST') . '/reset/' . config('app.key');

      if ($url_current != $reset_url) {
        // Check if database table exists
        if (! \Schema::hasTable('users')) {
          \Artisan::call('migrate', [
              '--force' => true,
          ]);
  
          \Artisan::call('db:seed', [
              '--force' => true,
          ]);
  
          \Artisan::call('key:generate');
  
          // If demo
          if (config('app.demo')) {
            \Artisan::call('db:seed', [
                '--force' => true,
                '--class' => 'DemoTableSeeder',
            ]);
          }
        }
      }

      view()->share('ip_address', \Platform\Controllers\Helper\Client::ip());
      view()->share('reseller', \Platform\Controllers\Core\Reseller::get());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
