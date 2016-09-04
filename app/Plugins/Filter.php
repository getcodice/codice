<?php

namespace Codice\Plugins;

use Codice\Support\Traits\Hookable;

class Filter
{
    use Hookable;

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
        $filters = self::getHookables($hook);

        foreach ($filters as $filter) {
            $value = call_user_func($filter['callable'], $value, $parameters);
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    protected static function getHookableType()
    {
        return 'Filter';
    }
}
