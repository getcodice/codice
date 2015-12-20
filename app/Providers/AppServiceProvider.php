<?php

namespace Codice\Providers;

use Blade;
use Codice\PluginManager;
use Codice\Reminders\ReminderService;
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
        // Boot plugins
        PluginManager::instance()->bootAll();

        Blade::directive('icon', function($name) {
            return '<span class="fa fa-' . substr($name, 2, -2) . '"></span>';
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        ReminderService::register(\Codice\Reminders\EmailReminder::class);

        // Register plugins
        PluginManager::instance()->registerAll();
    }
}
