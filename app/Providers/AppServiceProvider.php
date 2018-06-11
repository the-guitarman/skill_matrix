<?php

namespace App\Providers;

use Illuminate\Support\Facades\{App, Schema, Config};
use Illuminate\Support\ServiceProvider;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Sharing Data with all Views
        view()->share('app_name', Config::get('app.name'));

        //Carbon::setLocale(Config::get('app.locale'));

        //Dokument::observe(ImmutableObserver::class);
        //Dokument::observe(NotDeletableObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.debug') === true) {
            App::register(DebugbarServiceProvider::class);
        }
    }
}
