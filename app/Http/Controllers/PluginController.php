<?php

namespace Codice\Http\Controllers;

use Codice\PluginManager;
use Redirect;
use View;

class PluginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display listing of plugins.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $manager = PluginManager::instance();

        $allPlugins = $manager->readFromDirectory();
        $storage = $manager->getStorage();

        $plugins = [];
        foreach ($allPlugins as $pluginId => $plugin) {
            if (array_key_exists($pluginId, $storage)) {
                if ($storage[$pluginId]['enabled']) {
                    $pluginObject = $manager->findByIdentifier($pluginId);
                } else {
                    $pluginObject = $manager->loadPlugin($plugin['namespace'], $plugin['path']);
                }

                $plugins[strtolower($pluginId)] = [
                    'installed' => true,
                    'enabled' => $storage[$pluginId]['enabled'],
                    'details' => $pluginObject->pluginDetails(),
                ];
            } else {
                $pluginObject = $manager->loadPlugin($plugin['namespace'], $plugin['path']);

                $plugins[strtolower($pluginId)] = [
                    'installed' => false,
                    'enabled' => false,
                    'details' => $pluginObject->pluginDetails(),
                ];
            }
        }

        return View::make('plugin.index', [
            'plugins' => $plugins,
            'title' => trans('plugin.index.title'),
        ]);
    }

    public function getInstall($id)
    {
        $manager = PluginManager::instance();
        $id = $manager->normalizeDirectoryIdentifier($id);
        $manager->install($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.install'));
    }

    public function getUninstall($id)
    {
        $manager = PluginManager::instance();
        $id = $manager->normalizeDirectoryIdentifier($id);
        $manager->uninstall($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.uninstall'));
    }

    public function getEnable($id)
    {
        $manager = PluginManager::instance();
        $id = $manager->normalizeDirectoryIdentifier($id);
        $manager->enable($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.enable'));
    }

    public function getDisable($id)
    {
        $manager = PluginManager::instance();
        $id = $manager->normalizeDirectoryIdentifier($id);
        $manager->disable($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.disable'));
    }

    public function getRemove($id)
    {
        $manager = PluginManager::instance();
        $id = $manager->normalizeDirectoryIdentifier($id);
        $manager->remove($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.remove'));
    }
}
