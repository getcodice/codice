<?php

namespace Codice;

use App;
use Codice\Support\Traits\Singleton;
use Config;
use File;
use Lang;
use View;

class PluginManager {
    use Singleton;

    /**
     * The application instance, since Plugins are an extension of a Service Provider
     */
    protected $app;

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

        if (!file_exists(storage_path('app/plugins.json'))) {
            $this->setStorage([]);
        }

        $this->storage = $this->getStorage();

        $this->loadPlugins();
    }

    /**
     * Find enabled plugins and load them into the $this->plugins array.
     *
     * @return array
     */
    public function loadPlugins()
    {
        $this->plugins = [];

        foreach ($this->storage as $identifier => $class) {
            $this->plugins[$identifier] = $this->loadPlugin($identifier, $class);
        }

        return $this->plugins;
    }

    /**
     * Load all installed (enabled or not) plugins.
     *
     * @return array
     */
    public function loadAllPlugins()
    {
        $directories = glob(base_path('plugins/*'));
        $plugins = [];

        foreach ($directories as $directory) {
            $tmp = explode('/', $directory);
            $identifier = end($tmp);
            $class = $this->findClassByIdentifier($identifier);

            $plugins[$identifier] = $this->loadPlugin($identifier, $class);
        }

        return $plugins;
    }

    /**
     * Enable a single plugin.
     *
     * @param string $identifier Plugin's identifier (its directory)
     */
    public function enable($identifier)
    {
        $this->storage[$identifier] = $this->findClassByIdentifier($identifier);
        $this->setStorage($this->storage);
    }

    /**
     * Disable a single plugin.
     *
     * @param string $identifier Plugin's identifier (its directory)
     */
    public function disable($identifier)
    {
        unset($this->storage[$identifier]);
        $this->setStorage($this->storage);
    }

    /**
     * Determine if a plugin is disabled.
     *
     * @param string $identifier Plugin's identifier (its directory)
     * @return bool
     */
    public function isEnabled($identifier)
    {
        return isset($this->storage[$identifier]);
    }

    /**
     * Loads a single plugin into the manager.
     *
     * @param string $identifier Plugin's directory in /plugins
     * @param string $class Fully Qualified Name of the respective Plugin class
     * @return bool|PluginBase
     */
    public function loadPlugin($identifier, $class)
    {
        // Not a valid plugin!
        if (!class_exists($class)) {
            return false;
        }

        $classObj = new $class($this->app);

        // Check if plugin class inherits PluginBase and therefore an interface
        if (!$classObj instanceof PluginBase) {
            return false;
        }

        //$this->plugins[$identifier] = $classObj;

        return $classObj;
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
     * @param PluginBase $plugin
     * @param string $pluginId
     * @return void
     */
    public function registerPlugin($plugin, $pluginId)
    {
        if (!$plugin) {
            return;
        }

        $pluginPath = base_path('plugins/' . $pluginId);
        $pluginNamespace = strtolower($pluginId);

        $plugin->register();

        /*
         * Register language namespaces
         */
        $langPath = $pluginPath . '/lang';
        if (File::isDirectory($langPath)) {
            Lang::addNamespace($pluginNamespace, $langPath);
        }

        /*
         * Register configuration path
         */
        $configPath = $pluginPath . '/config';
        if (File::isDirectory($configPath)) {
            Config::package($pluginNamespace, $configPath, $pluginNamespace);
        }

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
        if (File::exists($routesFile)) {
            require $routesFile;
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

        foreach ($this->plugins as $plugin) {
            $this->bootPlugin($plugin);
        }

        $this->booted = true;
    }

    /**
     * Boot a single plugin object.
     *
     * @param PluginBase $plugin
     * @return void
     */
    public function bootPlugin($plugin)
    {
        $plugin->boot();
    }

    /**
     * Returns an array with all registered plugins.
     *
     * @param bool $withDisabled Wwhether to return disabled plugins
     * @return array Plugin identifier => plugin object
     */
    public function getPlugins($withDisabled = false)
    {
        if ($withDisabled) {
            return $this->plugins;
        }

        return array_filter($this->plugins, function($plugin) {
            $id = $this->getIdentifier($plugin);
            return $this->storage[$id]['enabled'];
        });
    }

    /**
     * Return database of plugin informations.
     *
     * @return array
     */
    public function getStorage()
    {
        return json_decode(file_get_contents(storage_path('app/plugins.json')), true);
    }

    /**
     * Write database of plugin informations.
     *
     * @param  array $content Plugins data
     * @return bool
     */
    public function setStorage($content)
    {
        return file_put_contents(storage_path('app/plugins.json'), json_encode($content));
    }

    /**
     * Return a Fully Qualified Name for plugin registration class based on its identifier.
     *
     * @param  string $identifier Plugin's identifier (its directory)
     * @return string
     */
    public function findClassByIdentifier($identifier)
    {
        $composerData = json_decode(file_get_contents(base_path("plugins/$identifier/composer.json")), true);

        return $composerData['extra']['codice-plugin-class'];
    }

    /**
     * Return a plugin registration class based on its identifier.
     *
     * @param  string $identifier Plugin's identifier (its directory)
     * @return PluginBase|null
     */
    public function findObjectByIdentifier($identifier)
    {
        return $this->plugins[$identifier];
    }

    /**
     * Returns a plugin identifier from a Plugin class name or object
     * @param  mixed $namespace Plugin class name or object
     * @return string Identifier in format of Vendor.Plugin
     */
    protected function getIdentifier($namespace)
    {
        // @TODO: is such method still needed?
    }
}
