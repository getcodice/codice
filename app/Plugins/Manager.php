<?php

namespace Codice\Plugins;

use App;
use Codice\Codice;
use Codice\Support\Traits\Singleton;
use Composer\Semver\Semver;
use File;
use Illuminate\Database\Migrations\Migrator;
use Lang;
use Log;
use Route;
use View;

class Manager
{
    use Singleton;

    /**
     * The application instance, since Plugins are an extension of a Service Provider
     */
    protected $app;

    /**
     * Composer runtime autoloader object.
     */
    protected $autoloader;

    /**
     * Container object used for storing plugin information objects.
     */
    protected $plugins;

    /**
     * Storage holding enabled plugins.
     *
     * Key is plugin directory (referenced as "identifier" in code) and the value is Fully Qualified Name
     * of the respective Plugin class (referenced as "class" in the code).
     */
    protected $storage;

    /**
     * @var bool Check if all plugins have had the register() method called.
     */
    protected $registered = false;

    /**
     * @var bool Check if all plugins have had the boot() method called.
     */
    protected $booted = false;

    /**
     * Initializes the plugin manager.
     */
    protected function init()
    {
        $this->app = App::make('app');

        $storageFile = storage_path('app/plugins.json');

        if (!file_exists($storageFile) || filesize($storageFile) == 0) {
            $this->setInitialStorage();
            Log::info('Plugins storage is empty or could not be found - empty array initialized.');
        }

        $this->storage = $this->getStorage();

        $this->autoloader = require base_path('vendor/autoload.php');

        $this->loadPlugins();
    }

    /**
     * Gets identifiers of all plugins found in the directory, regardless of their status.
     *
     * @return string[]
     */
    public function getAllPlugins()
    {
        $directories = glob(base_path('plugins/*'));
        $plugins = [];

        foreach ($directories as $directory) {
            $tmp = explode('/', $directory);
            $identifier = end($tmp);

            $plugins[] = $identifier;
        }

        return $plugins;
    }

    /**
     * Find enabled plugins and load them into the $this->plugins array.
     *
     * @return array
     */
    public function loadPlugins()
    {
        $this->plugins = [];

        foreach ($this->getEnabledPlugins($this->storage) as $identifier) {
            $this->plugins[$identifier] = $this->loadPlugin($identifier);
        }

        return $this->plugins;
    }

    /**
     * Loads a single plugin into the manager.
     *
     * @param string $identifier
     * @return bool|Plugin
     */
    public function loadPlugin($identifier)
    {
        require_once base_path("plugins/$identifier/Plugin.php");

        $class = $this->getPluginFqn($identifier, 'Plugin');

        // Not a valid plugin!
        if (!class_exists($class)) {
            return false;
        }

        $pluginObject = new $class($this->app);

        // Check if plugin class inherits Plugin and therefore an interface
        if (!$pluginObject instanceof Plugin) {
            return false;
        }

        // Add runtime autoloader rule
        $this->registerAutoloader($identifier);

        return $pluginObject;
    }

    /**
     * Run the register() method on all plugins. Can only be called once.
     * @return void
     */
    public function registerAll()
    {
        if ($this->registered) {
            return;
        }

        foreach ($this->plugins as $pluginId => $plugin) {
            $this->registerPlugin($plugin, $pluginId);
        }

        $this->registered = true;
    }

    /**
     * Register a single plugin object.
     *
     * @param Plugin $plugin
     * @param string $identifier
     * @return void
     */
    public function registerPlugin(Plugin $plugin, $identifier)
    {
        if (!$plugin) {
            return;
        }

        $pluginPath = base_path("plugins/$identifier");
        $pluginNamespace = strtolower($identifier);

        $plugin->register();

        /*
         * Register configuration path
         */
        // @FIXME Config::package() doesn't exists

        /*
         * Register views path
         */
        $viewsPath = $pluginPath . '/views';
        if (File::isDirectory($viewsPath)) {
            View::addNamespace($pluginNamespace, $viewsPath);
        }

        /*
         * Add init, if available
         */
        $initFile = $pluginPath . '/init.php';
        if (File::exists($initFile)) {
            require $initFile;
        }

        /*
         * Add routes, if available
         */
        $routesFile = $pluginPath . '/routes.php';
        $controllersNamespace = $this->getPluginFqn($identifier, 'Controllers');
        if (File::exists($routesFile)) {
            Route::group(['middleware' => 'web', 'namespace' => $controllersNamespace], function () use ($routesFile) {
                require $routesFile;
            });
        }
    }

    /**
     * Run the boot() method on all plugins. Can only be called once.
     */
    public function bootAll()
    {
        if ($this->booted) {
            return;
        }

        foreach ($this->plugins as $pluginId => $plugin) {
            $this->bootPlugin($plugin, $pluginId);
        }

        $this->booted = true;
    }

    /**
     * Boot a single plugin object.
     *
     * @param Plugin $plugin
     * @return void
     */
    public function bootPlugin(Plugin $plugin, $identifier)
    {
        $pluginPath = base_path("plugins/$identifier");
        $pluginNamespace = strtolower($identifier);

        $plugin->boot();

        /*
         * Register language namespaces
         */
        $langPath = $pluginPath . '/lang';
        if (File::isDirectory($langPath)) {
            Lang::addNamespace($pluginNamespace, $langPath);
        }
    }

    /**
     * Enable a single plugin.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return bool
     */
    public function enable($identifier)
    {
        $this->storage[$identifier] = true;

        $this->setStorage($this->storage);

        return true;
    }

    /**
     * Disable a single plugin.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return bool
     */
    public function disable($identifier)
    {
        $this->storage[$identifier] = false;

        $this->setStorage($this->storage);

        return true;
    }

    /**
     * Install a single plugin.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return bool
     */
    public function install($identifier)
    {
        if ($this->isInstalled($identifier)) {
            return true;
        }

        // Check plugin requirements
        $requirements = $this->pluginDetails($identifier)['require'];
        if (!$this->checkRequirements($requirements)) {
            return false;
        }

        $this->enable($identifier);

        // Load installed plugin's object so it can be accessed in next step
        $this->plugins[$identifier] = $plugin = $this->loadPlugin($identifier);

        // Run plugin's  migrations
        $migrator = self::prepareMigrator($identifier);
        $migrator->run([$plugin->path('migrations')]);

        // Run plugin-specific install actions
        $plugin->install();

        return true;
    }

    /**
     * Uninstall a single plugin.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return bool
     */
    public function uninstall($identifier)
    {
        if (!$this->isInstalled($identifier)) {
            return false;
        }

        $this->disable($identifier);

        // Load plugin
        $plugin = $this->loadPlugin($identifier);

        // Call its uninstall() method
        $plugin->uninstall();

        // Roll back its migrations
        $migrator = self::prepareMigrator($identifier);
        $migrator->rollback([$plugin->path('migrations')]);

        // Remove plugin's directory
        File::deleteDirectory($plugin->path());

        // Remove plugin entry from the storage
        unset($this->storage[$identifier]);
        $this->setStorage($this->storage);

        return true;
    }

    /**
     * Determine if a plugin is disabled.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return bool
     */
    public function isDisabled($identifier)
    {
        return $this->storage[$identifier] === false;
    }

    /**
     * Determine if a plugin is enabled.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return bool
     */
    public function isEnabled($identifier)
    {
        return $this->storage[$identifier] === true;
    }

    /**
     * Determine if a plugin is installed.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return bool
     */
    public function isInstalled($identifier)
    {
        return isset($this->storage[$identifier]);
    }

    /**
     * Returns array of plugin details.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return string[]
     */
    public function pluginDetails($identifier)
    {
        $json = file_get_contents(base_path("plugins/$identifier/plugin.json"));

        return json_decode($json, true);
    }

    /**
     * Checks requirements for a given plugin.
     *
     * @param  array $requirements
     * @return bool
     */
    public function checkRequirements($requirements)
    {
        foreach ($requirements as $requirement => $constraint) {
            if (!$this->checkRequirement($requirement, $constraint)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if given requirement is met, based on its constraint.
     *
     * @param  string $requirement
     * @param  string $constraint
     * @return bool
     */
    protected function checkRequirement($requirement, $constraint)
    {
        if ($requirement === 'codice') {
            $version = (new Codice)->getVersion();
        } elseif ($requirement === 'php') {
            $version = phpversion();
        } elseif (substr($requirement, 0, 4) === 'ext-') {
            $extension = substr($requirement, 4);
            $version = phpversion($extension);
        } elseif (substr($requirement, 0, 7) === 'plugin-') {
            $plugin = substr($requirement, 7);

            if (!$this->isEnabled($plugin)) {
                return false;
            }

            $version = $this->pluginDetails($plugin)['version'];
        } else {
            return false;
        }

        return Semver::satisfies($version, $constraint);
    }

    /**
     * Return a Fully Qualified Name for plugin class or subnamespace.
     *
     * @param  string $identifier Plugin's identifier (its directory)
     * @param  string $for        Class or subnamespace
     * @return string
     */
    protected function getPluginFqn($identifier, $for = '')
    {
        return 'CodicePlugin\\' . studly_case($identifier) . '\\' . $for;
    }

    /**
     * Return database of plugin informations.
     *
     * @return array
     */
    protected function getStorage()
    {
        return json_decode(file_get_contents(storage_path('app/plugins.json')), true);
    }

    /**
     * Write database of plugin informations.
     *
     * @param  array $content Plugins data
     * @return bool
     */
    protected function setStorage($content)
    {
        return file_put_contents(storage_path('app/plugins.json'), json_encode($content));
    }

    /**
     * Write initial structure for plugis storage.
     *
     * @return bool
     */
    protected function setInitialStorage()
    {
        $this->setStorage([]);
    }


    /**
     * Return a plugin registration class based on its identifier.
     *
     * @param  string $identifier Plugin's identifier (its directory)
     * @return Plugin|null
     */
    protected function findObjectByIdentifier($identifier)
    {
        return $this->plugins[$identifier];
    }

    /**
     * Filters plugin storage to return only enabled plugins.
     *
     * @param  array $storage Array being plugin storage
     * @return array
     */
    protected function getEnabledPlugins($storage)
    {
        return array_keys(array_filter($storage, function($state) {
            return $state === true;
        }));
    }

    /**
     * Creates migrator object set up for a specified plugin.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return Migrator
     */
    protected function prepareMigrator($identifier)
    {
        $repository = new MigrationRepository($this->app['db'], $identifier);

        if (!$repository->repositoryExists()) {
            $repository->createRepository();
        }

        return new Migrator($repository, $this->app['db'], $this->app['files']);
    }

    /**
     * Registers runtime autoloader rule for files inside the folder of given plugin.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return bool
     */
    protected function registerAutoloader($identifier)
    {
        $namespace = $this->getPluginFqn($identifier);
        $path = base_path('plugins/' . $identifier);

        $this->autoloader->setPsr4($namespace, $path);

        return true;
    }
}
