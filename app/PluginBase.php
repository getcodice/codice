<?php

namespace Codice;

use Illuminate\Support\ServiceProvider;

class PluginBase extends ServiceProvider
{
    /**
     * Return basic plugin informations.
     *
     * Obligatory keys: name, descrption, author, version
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [];
    }

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
}
