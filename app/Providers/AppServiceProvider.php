<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Traduzindo o Carbon
//        \Carbon\Carbon::setLocale('pt_BR');

        \View::composer('*', function ($view) {
//            $channels = \Cache::rememberForever('channels', function () {
//                return Channel::all();
//            });
            $channels = Channel::all();

            $view->with('channels', $channels);
        });
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
