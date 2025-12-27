<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerSwalDirective();
    }

    private function registerSwalDirective()
    {
        Blade::directive('swal', function ($e) {
            $swaljs = asset('assets/js/plugins/notifications/sweet_alert.min.js');

            $script = implode("\n", [
                "view()->startPush('scripts');",
                "\$html = '<script src=\"$swaljs\"></script>';",
                "\$html .= '<script>';",
                "if (session()->has('toast.success')) { \$html .= toast('success'); }",
                "if (session()->has('toast.error')) { \$html .= toast('error'); }",
                "\$html .= '</script>';",
                "echo \$html;",
                "view()->stopPush('scripts');",
            ]);

            return "<?php $script; ?>";
        });
    }
}
