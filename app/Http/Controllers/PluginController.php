<?php

namespace Codice\Http\Controllers;

use Codice\Plugins\Manager;
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
        $manager = Manager::instance();

        $allPlugins = $manager->loadAllPlugins();

        $plugins = [];
        foreach ($allPlugins as $identifier => $class) {
            $plugins[$identifier] = [
                'details' => $class->pluginDetails(),
                'state' => $manager->isInstalled($identifier)
                    ? ($manager->isEnabled($identifier) ? 'enabled' : 'disabled')
                    : 'not-installed',
            ];
        }

        return View::make('plugin.index', [
            'plugins' => $plugins,
            'title' => trans('plugin.index.title'),
        ]);
    }

    public function getEnable($id)
    {
        Manager::instance()->enable($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.enable'));
    }

    public function getDisable($id)
    {
        Manager::instance()->disable($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.disable'));
    }

    public function getInstall($id)
    {
        Manager::instance()->install($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.install'));
    }

    public function getUninstall($id)
    {
        Manager::instance()->uninstall($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.uninstall'));
    }
}
