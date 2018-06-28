<?php

namespace App\Providers;

use Illuminate\Support\Facades\{App, Schema, Config};
use Illuminate\Support\ServiceProvider;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Carbon\Carbon;

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
        view()->share('app_copyright', $this->copyright());
        view()->share('grade_rgb_colors', Config::get('grade.rgb_colors'));

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

    protected function copyright() 
    {
        $result = 2018;
        $currentYear = Carbon::now()->format('Y');
        if ($result < $currentYear) {
            $result .= " - $currentYear";
        }
        return 'Copyright '.$result;
    }
}
