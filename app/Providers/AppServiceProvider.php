<?php

namespace Codice\Providers;

use Blade;
use Codice\MenuManager;
use Codice\PluginManager;
use Codice\Reminders\ReminderService;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register menu manager instances
        // They still need to be located in the Container in order to allow
        // menu modification using plugins.
        $this->app->singleton('menu.main', function ($app) {
            return new MenuManager;
        });
        $this->app->singleton('menu.user', function ($app) {
            return new MenuManager;
        });

        // Register application's menus
        View::composer('app', function ($view) {
            $this->registerMainMenu();
            $this->registerUserMenu();
        });

        // Boot plugins
        PluginManager::instance()->bootAll();

        Blade::directive('hook', function($name) {
            return "<?php event('" . substr($name, 2, -2) . "'); ?>";
        });

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
        // Register service for email reminders
        ReminderService::register(\Codice\Reminders\EmailReminder::class);

        // Register plugins
        PluginManager::instance()->registerAll();
    }

    private function registerMainMenu()
    {
        $m = $this->app->make('menu.main');

        $m->add('note.create', 'app.menu.add', 'plus', 5);
        $m->add('labels', 'app.menu.labels', 'tags', 10, ['label']);
        $m->add('reminders', 'app.menu.reminders', 'bell', 15);
        $m->add('upcoming', 'app.menu.upcoming', 'calendar', 20);
    }

    private function registerUserMenu()
    {
        $m = $this->app->make('menu.user');

        $m->add('settings', 'app.menu.settings', 'cog fa-fw', 5);
        $m->add('plugins', 'app.menu.plugins', 'plug fa-fw', 10);
        $m->add('stats', 'app.menu.stats', 'bar-chart fa-fw', 15);
        $m->add('about', 'app.menu.about', 'info-circle fa-fw', 20);
        $m->add('user.logout', 'app.menu.logout', 'sign-out fa-fw', 25);
    }
}
