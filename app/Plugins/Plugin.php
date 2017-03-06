<?php

namespace Codice\Plugins;

use Illuminate\Support\ServiceProvider;
use ReflectionClass;

abstract class Plugin extends ServiceProvider
{
    /**
     * All operations to perform on plugin installation.
     */
    public function install()
    {

    }

    /**
     * All operations to perform on plugin uninstallation.
     */
    public function uninstall()
    {

    }

    /**
     * Operation performed when ServiceProvider (plugin) is registered.
     */
    public function register()
    {

    }

    /**
     * Operation performed when ServiceProvider (plugin) is booted.
     */
    public function boot()
    {

    }

    /**
     * Return plugin's identifier from its parent class.
     *
     * @return string
     */
    public function getPluginID()
    {
        static $id = null;

        if ($id === null) {
            $reflection = new ReflectionClass(get_class($this));
            $dirname = dirname($reflection->getFileName());

            $id = array_reverse(explode(DIRECTORY_SEPARATOR, $dirname))[0];
        }

        return $id;
    }

    /**
     * Return plugin's path from its parent class.
     *
     * @param  string $path Subpath within plugins's directory
     * @return string
     */
    public function path($path = '')
    {
        $id = $this->getPluginID();

        return base_path("plugins/$id/$path");
    }
}
