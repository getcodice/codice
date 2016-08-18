<?php

namespace Codice\Plugins;

use Log;

class Action
{
    /**
     * @var array Holds all currently registered actions
     */
    protected static $actions;

    /**
     * Register new action for given hook.
     *
     * @param string $hook Name of the hook
     * @param string $actionName Name of the action, must be unique within a hook
     * @param callable $callable Callable containing action to run
     * @param int $priority Order of calling action, when priority is equal order is undefined
     * @return bool
     */
    public static function register($hook, $actionName, callable $callable, $priority = 10)
    {
        if (self::isRegistered($hook, $actionName)) {
            Log::warning("Action '$actionName' was already registered within '$hook' hook and has been overwritten.");
        }

        self::$actions[$hook][$actionName] = [
            'priority' => $priority,
            'callable' => $callable,
        ];

        return true;
    }

    /**
     * Check whether action of given name has been registered within a specified hook.
     *
     * @param string $hook Name of the hook
     * @param string $actionName Name of the action within a hook
     * @return bool
     */
    public static function isRegistered($hook, $actionName)
    {
        return isset(self::$actions[$hook][$actionName]);
    }

    /**
     * Deregister action from given hook.
     *
     * Deregistration must happen before Action::call() to have an effect.
     *
     * @param string $hook Name of the hook
     * @param string $actionName Name of the action
     * @return bool
     */
    public static function deregister($hook, $actionName)
    {
        unset(self::$actions[$hook][$actionName]);

        return true;
    }

    /**
     * Call all actions registered for a given hook.
     *
     * @param string $hook Name of the hook
     * @param array $parameters Parameters which will be passed to callback
     */
    public static function call($hook, array $parameters = [])
    {
        $actions = self::getActions($hook);

        foreach ($actions as $action) {
            call_user_func($action['callable'], $parameters);
        }
    }

    /**
     * Get (sorted) list of all actions assigned to a hook.
     *
     * @param string $hook Name of the hook
     * @return array
     */
    private static function getActions($hook)
    {
        if (isset(self::$actions[$hook])) {
            $actions = self::$actions[$hook];
        } else {
            $actions = [];
        }

        return self::sortActions($actions);
    }

    /**
     * Sort actions by their priority.
     *
     * @param array $actions Array of unsorted actions
     * @return array
     */
    private static function sortActions($actions)
    {
        usort($actions, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }

            return $a['priority'] < $b['priority'] ? -1 : 1;
        });

        return $actions;
    }
}
