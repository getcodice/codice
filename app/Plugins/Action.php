<?php

namespace Codice\Plugins;

use Codice\Support\Traits\Hookable;

class Action
{
    use Hookable;

    /**
     * Call all actions registered for a given hook.
     *
     * @param string $hook Name of the hook
     * @param array $parameters Parameters which will be passed to callback
     */
    public static function call($hook, array $parameters = [])
    {
        $actions = self::getHookables($hook);

        foreach ($actions as $action) {
            call_user_func($action['callable'], $parameters);
        }
    }

    /**
     * @inheritdoc
     */
    protected static function getHookableType()
    {
        return 'Action';
    }
}
