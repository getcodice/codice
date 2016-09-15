<?php

namespace Codice\Providers;

use Blade;
use Codice\Plugins\Action;
use Codice\Plugins\Menu;
use Codice\Plugins\Manager as PluginManager;
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
            return new Menu;
        });
        $this->app->singleton('menu.user', function ($app) {
            return new Menu;
        });

        // Register application's menus
        View::composer('app', function ($view) {
            $this->registerMainMenu();
            $this->registerUserMenu();
        });

        // Boot plugins
        PluginManager::instance()->bootAll();

        Blade::directive('hook', function($parameters) {
            // Strip redundant parenthesis
            $parameters = substr($parameters, 1, -1);

            return "<?php Codice\\Plugins\\Action::call(" . $parameters . "); ?>";
        });

        Blade::directive('icon', function($name) {
            return '<span class="fa fa-' . substr($name, 2, -2) . '"></span>';
        });

        // Register hook for shutdown actions
        register_shutdown_function(function() {
            /**
             * Executed right before script is terminated (it's a handler for register_shutdown_function())
             *
             * @since 0.3
             */
            Action::call('core.shutdown');
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

        // Register laravel-ide-helper unless we are in prouduction env
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

    }

    private function registerMainMenu()
    {
        $m = $this->app->make('menu.main');

        $m->add('note.create', 'app.menu.add', 'plus', 5);
        $m->add('calendar', 'app.menu.calendar', 'calendar', 10);
        $m->add('labels', 'app.menu.labels', 'tags', 15, ['label']);
        $m->add('reminders', 'app.menu.reminders', 'bell', 20);
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
