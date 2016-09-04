<?php

namespace Codice\Support\Traits;

use Exception;
use Log;

trait Hookable
{
    /**
     * @var array Holds all currently registered hookables
     */
    protected static $hookables;

    /**
     * Register new hookable for given hook.
     *
     * @param string $hook Name of the hook
     * @param string $hookableName Name of the hookable, must be unique within a hookable of given type
     * @param callable $callable Callable containing code to run
     * @param int $priority Order of calling hookables, when priority is equal order is undefined
     * @return bool
     */
    public static function register($hook, $hookableName, callable $callable, $priority = 10)
    {
        $hookableType = self::getHookableType();

        if (self::isRegistered($hook, $hookableName)) {
            Log::warning("$hookableType '$hookableName' was already registered within '$hook' hook and has been overwritten.");
        }

        self::$hookables[$hook][$hookableName] = [
            'priority' => $priority,
            'callable' => $callable,
        ];

        return true;
    }

    /**
     * Check whether hookable of given name has been registered within a specified hook.
     *
     * @param string $hook Name of the hook
     * @param string $hookableName Name of the hookable within a hook
     * @return bool
     */
    public static function isRegistered($hook, $hookableName)
    {
        return isset(self::$hookables[$hook][$hookableName]);
    }

    /**
     * Deregister hookable from given hook.
     *
     * Deregistration must happen before call() method is run to have an effect.
     *
     * @param string $hook Name of the hook
     * @param string $hookableName Name of the hookable
     * @return bool
     */
    public static function deregister($hook, $hookableName)
    {
        unset(self::$hookables[$hook][$hookableName]);

        return true;
    }

    /**
     * Get (sorted) list of all hookables assigned to a hook.
     *
     * @param string $hook Name of the hook
     * @return array
     */
    private static function getHookables($hook)
    {
        $hookables = isset(self::$hookables[$hook]) ? self::$hookables[$hook] : [];

        return self::sortHookables($hookables);
    }

    /**
     * Sort hookables by their priority.
     *
     * @param array $hookables Array of unsorted hookables
     * @return array
     */
    protected static function sortHookables($hookables)
    {
        usort($hookables, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }

            return $a['priority'] < $b['priority'] ? -1 : 1;
        });

        return $hookables;
    }

    /**
     * Return human readable name of hookable
     *
     * @throws Exception
     * @return string
     */
    protected static function getHookableType()
    {
        throw new Exception('getHookableType() must be implemented');
    }
}
