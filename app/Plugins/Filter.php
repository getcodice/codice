<?php

namespace Codice\Plugins;

use Log;

class Filter
{
    /**
     * @var array Holds all currently registered filters
     */
    protected static $filters;

    /**
     * @var array Holds all values possible to filter to save their state between each filter
     */
    protected static $filtered;

    /**
     * Register new filter for given hook.
     *
     * @param string $hook Name of the hook
     * @param string $filterName Name of the filter, must be unique within a hook
     * @param callable $callable Callable containing filter to run
     * @param int $priority Order of calling filter, when priority is equal order is undefined
     * @return bool
     */
    public static function register($hook, $filterName, callable $callable, $priority = 10)
    {
        if (self::isRegistered($hook, $filterName)) {
            Log::warning("Filter '$filterName' was already registered within '$hook' hook and has been overwritten.");
        }

        self::$filters[$hook][$filterName] = [
            'priority' => $priority,
            'callable' => $callable,
        ];

        return true;
    }

    /**
     * Check whether filter of given name has been registered within a specified hook.
     *
     * @param string $hook Name of the hook
     * @param string $filterName Name of the filter within a hook
     * @return bool
     */
    public static function isRegistered($hook, $filterName)
    {
        return isset(self::$filters[$hook][$filterName]);
    }

    /**
     * Deregister filter from given hook.
     *
     * Deregistration must happen before Filter::call() to have an effect.
     *
     * @param string $hook Name of the hook
     * @param string $filterName Name of the filter
     * @return bool
     */
    public static function deregister($hook, $filterName)
    {
        unset(self::$filters[$hook][$filterName]);

        return true;
    }

    /**
     * Call all filters registered for a given hook.
     *
     * @param string $hook Name of the hook
     * @param mixed $value Value to run filters on
     * @param array $parameters Parameters which will be passed to callback
     * @return mixed Filtered value
     */
    public static function call($hook, $value, array $parameters = [])
    {
        $filters = self::getFilters($hook);

        foreach ($filters as $filter) {
            $value = call_user_func($filter['callable'], $value, $parameters);
        }

        return $value;
    }

    /**
     * Get (sorted) list of all filters assigned to a hook.
     *
     * @param string $hook Name of the hook
     * @return array
     */
    private static function getFilters($hook)
    {
        if (isset(self::$filters[$hook])) {
            $filters = self::$filters[$hook];
        } else {
            $filters = [];
        }

        return self::sortFilters($filters);
    }

    /**
     * Sort filters by their priority.
     *
     * @param array $filters Array of unsorted filters
     * @return array
     */
    private static function sortFilters($filters)
    {
        usort($filters, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }

            return $a['priority'] < $b['priority'] ? -1 : 1;
        });

        return $filters;
    }
}
