<?php

namespace Codice\Http\Controllers;

use Redirect;
use View;

class PluginController extends Controller
{
    /**
     * Displays listing of plugins.
     *
     * GET /plugins (as plugins)
     */
    public function getIndex()
    {
        $manager = app('plugin.manager');

        $allPlugins = $manager->getAllPlugins();

        $plugins = [];
        foreach ($allPlugins as $identifier) {
            $plugins[$identifier] = [
                'details' => $manager->pluginDetails($identifier),
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

    /**
     * Enables single plugin.
     *
     * GET /plugin/{id}/enable (as plugin.enable)
     */
    public function getEnable($id)
    {
        app('plugin.manager')->enable($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.enable'));
    }

    /**
     * Disables single plugin.
     *
     * GET /plugin/{id}/disable (as plugin.disable)
     */
    public function getDisable($id)
    {
        app('plugin.manager')->disable($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.disable'));
    }

    /**
     * Installs single plugin.
     *
     * GET /plugin/{id}/install (as plugin.install)
     */
    public function getInstall($id)
    {
        $status = app('plugin.manager')->install($id);

        return Redirect::route('plugins')
                ->with('message', trans($status ? 'plugin.success.install' : 'plugin.error.requirements'))
                ->with('message_type', $status ? 'info' : 'danger');
    }

    /**
     * Removes single plugin.
     *
     * GET /plugin/{id}/uninstall (as plugin.uninstall)
     */
    public function getUninstall($id)
    {
        app('plugin.manager')->uninstall($id);

        return Redirect::route('plugins')->with('message', trans('plugin.success.uninstall'));
    }
}
