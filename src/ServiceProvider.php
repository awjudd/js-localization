<?php

namespace Awjudd\JavaScriptLocalization;

use Awjudd\JavaScriptLocalization\Console\Commands\GenerateCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/js-localization.php' => config_path('js-localization.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/js-localization.php', 'js-localization'
        );
    }
}