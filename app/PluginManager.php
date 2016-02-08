<?php

namespace Codice;

use App;
use Codice\Support\Traits\Singleton;
use Config;
use File;
use Lang;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
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
     * Storage holding most important informations about installed plugins.
     *
     * - key is plugin ID (Vendor.Plugin), and it has assigned:
     * - plugin's path
     * - plugin's namespace
     * - whether plugin is enabled (bool)
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
     * Find all available plugins and load them in to the $this->plugins array.
     *
     * @return array
     */
    public function loadPlugins()
    {
        $this->plugins = [];

        foreach ($this->storage as $identifier => $plugin) {
            if ($plugin['enabled']) {
                $this->loadPlugin($plugin['namespace'], $plugin['path']);
            }
        }

        return $this->plugins;
    }

    /**
     * Install new plugin.
     *
     * @param string $id Identifier in format of Vendor.Plugin
     */
    public function install($id)
    {
        // We need to load plugin manuall as it's not in storage yet
        $pluginData = $this->readFromDirectory()[$id];
        $pluginObject = $this->loadPlugin($pluginData['namespace'], $pluginData['path']);

        // Call plugin's install() method
        $pluginObject->install();

        // Add plugin to plugins storage
        $this->storage[$id] = [
            'path' => $pluginData['path'],
            'namespace' => $pluginData['namespace'],
            'enabled' => true,
        ];
        $this->setStorage($this->storage);
    }

    /**
     * Completely delete a plugin from the system.
     *
     * @param string $id Identifier in format of Vendor.Plugin
     */
    public function uninstall($id)
    {
        // Call plugin's uninstall() method
        $pluginObject = $this->findByIdentifier($id);
        $pluginObject->uninstall();

        // Delete from file system
        File::deleteDirectory($this->storage[$id]['path']);

        // Remove plugin from plugins storage
        unset($this->storage[$id]);
        $this->setStorage($this->storage);
    }

    /**
     * Enable a single plugin.
     *
     * @param string $id Identifier in format of Vendor.Plugin
     */
    public function enable($id)
    {
        $this->storage[$id]['enabled'] = true;
        $this->setStorage($this->storage);
    }

    /**
     * Disable a single plugin.
     *
     * @param string $id Identifier in format of Vendor.Plugin
     */
    public function disable($id)
    {
        $this->storage[$id]['enabled'] = false;
        $this->setStorage($this->storage);
    }

    /**
     * Determine if a plugin is disabled.
     *
     * @param string $id Identifier in format of Vendor.Plugin
     * @return bool
     */
    public function isDisabled($id)
    {
        return !$this->storage[$id]['enabled'];
    }

    /**
     * Remove plugin which has not been installed yet.
     *
     * @param string $id Identifier in format of Vendor.Plugin
     */
    public function remove($id)
    {
        $pluginData = $this->readFromDirectory()[$id];

        File::deleteDirectory($pluginData['path']);
    }

    /**
     * Loads a single plugin in to the manager.
     * @param string $namespace Eg: Acme\Blog
     * @param string $path Eg: plugins/acme/blog
     * @return bool|PluginBase
     */
    public function loadPlugin($namespace, $path)
    {
        $className = $namespace.'\Plugin';
        $classPath = $path.'/Plugin.php';

        // Autoloader failed?
        if (!class_exists($className)) {
            include_once $classPath;
        }

        // Not a valid plugin!
        if (!class_exists($className)) {
            return false;
        }

        $classObj = new $className($this->app);

        // Check if plugin class inherits PluginBase and therefore an interface
        if (!$classObj instanceof PluginBase) {
            return false;
        }

        $classId = $this->getIdentifier($classObj);

        $this->plugins[$classId] = $classObj;

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

        $pluginPath = $this->storage[$pluginId]['path'];
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
     * Check if a plugin exists and is enabled.
     *
     * @param   string $id Identifier in format of Vendor.Plugin
     * @return  bool
     */
    public function exists($id)
    {
        if (!isset($this->storage[$id])) {
            return false;
        }

        return $this->storage[$id]['enabled'];
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
     * Return a plugin registration class based on its identifier.
     *
     * @param  string $identifier Plugin's identifier in format ofVendor.Plugin
     * @return PluginBase
     */
    public function findByIdentifier($identifier)
    {
        if (!isset($this->plugins[$identifier])) {
            $identifier = $this->normalizeIdentifier($identifier);
        }

        if (!isset($this->plugins[$identifier])) {
            return null;
        }

        return $this->plugins[$identifier];
    }

    /**
     * Read all plugins present in /plugins directory.
     */
    public function readFromDirectory()
    {
        $result = [];

        foreach ($this->getVendorAndPluginNames() as $vendorName => $vendorList) {
            foreach ($vendorList as $pluginName => $pluginPath) {
                $namespace = '\\'.$vendorName.'\\'.$pluginName;
                $namespace = self::normalizeClassName($namespace);

                $identifier = $this->getIdentifier($namespace);

                $result[$identifier] = [
                    'path' => $pluginPath,
                    'namespace' => $namespace,
                ];
            }
        }

        return $result;
    }

    /**
     * Returns a 2 dimensional array of vendors and their plugins.
     */
    protected function getVendorAndPluginNames()
    {
        $plugins = [];

        $dirPath = base_path('plugins/');
        if (!File::isDirectory($dirPath)) {
            return $plugins;
        }

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::FOLLOW_SYMLINKS)
        );
        $it->setMaxDepth(2);
        $it->rewind();

        while ($it->valid()) {
            if (($it->getDepth() > 1) && $it->isFile() && (strtolower($it->getFilename()) == "plugin.php")) {
                $filePath = dirname($it->getPathname());
                $pluginName = basename($filePath);
                $vendorName = basename(dirname($filePath));
                $plugins[$vendorName][$pluginName] = $filePath;
            }

            $it->next();
        }

        return $plugins;
    }

    /**
     * Returns a plugin identifier from a Plugin class name or object
     * @param  mixed $namespace Plugin class name or object
     * @return string Identifier in format of Vendor.Plugin
     */
    protected function getIdentifier($namespace)
    {
        $namespace = self::normalizeClassName($namespace);
        if (strpos($namespace, '\\') === null) {
            return $namespace;
        }

        $parts = explode('\\', $namespace);
        $slice = array_slice($parts, 1, 2);
        $namespace = implode('.', $slice);
        return $namespace;
    }

    /**
     * Take a human plugin code (vendor.pluginname) and make it authentic (Vendor.PluginName).
     *
     * @param  string $identifier
     * @return string
     */
    public function normalizeIdentifier($identifier)
    {
        foreach ($this->plugins as $id => $object) {
            if (strtolower($id) == strtolower($identifier)) {
                return $id;
            }
        }

        return $identifier;
    }

    /**
     * Take a human plugin code (vendor.pluginname) and make it authentic (Vendor.PluginName);
     * this function scans /plugins directory, not $this->plugins.
     *
     * @param  string $identifier
     * @return string
     */
    public function normalizeDirectoryIdentifier($identifier)
    {
        $directory = $this->readFromDirectory();

        foreach ($directory as $id => $data) {
            if (strtolower($id) == strtolower($identifier)) {
                return $id;
            }
        }

        return $identifier;
    }

    /**
     * Remove the starting slash from a class namespace.
     *
     * @param  object|string $name Class object or fully qualified name
     * @return string
     */
    protected static function normalizeClassName($name)
    {
        if (is_object($name))
            $name = get_class($name);

        $name = '\\'.ltrim($name, '\\');
        return $name;
    }
}
