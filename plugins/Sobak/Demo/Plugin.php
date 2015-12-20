<?php

namespace Sobak\Demo;

use Codice\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'Demo',
            'description' => 'Demonstrational plugin for Codice',
            'author' => 'Sobak',
            'version' => '0.1',
        ];
    }

    public function register()
    {
        //dd('lakukaracza');
    }
}
