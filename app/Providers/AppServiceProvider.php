<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (preg_match('/^https/', config('app.url'))) {
            URL::forceScheme('https');
        }


        Blade::directive("hasaccess", function($expression) {
            return "<?php if(\App\Helpers\AccessControlHelper::checkCurrentAccessControl({$expression})) { ?>";
        });

        Blade::directive("elseaccess", function() {
            return "<?php } else { ?>";
        });

        Blade::directive("endhasaccess", function() {
            return "<?php } ?>";
        });

        ini_set('memory_limit','512M');
    }
}
