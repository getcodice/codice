<?php

namespace Codice;

use Route;
use URL;

class MenuManager {
    /**
     * @var array Added menu items.
     */
    protected $items = [];

    /**
     * Add menu item.
     *
     * @param string $route Name of the target route
     * @param string $transKey Key in translation file which will be used as a name
     * @param string $icon Valid fontawesome icon class, without fa-* prefix
     * @param int $position Position of the menu item
     * @param array $additionalRoutes Additional routes which should mark item as active
     */
    public function add($route, $translationKey, $icon, $position = null, $additionalRoutes = [])
    {
        $this->items[$route] = [
            'route' => $route,
            'name' => trans($translationKey),
            'icon' => icon($icon),
            'additionalRoutes' => $additionalRoutes,
        ];

        if ($position === null) {
            $lastPosition = max(array_column($this->items, 'position'));
            $position = $lastPosition + 1;
        }

        $this->items[$route]['position'] = $position;
    }

    /**
     * Remove menu item.
     *
     * @param string $route Target route of the removed element
     */
    public function remove($route)
    {
        unset($this->items[$route]);
    }

    public function getItems()
    {
        // Sort items by their position
        usort($this->items, function ($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $this->items;
    }

    /**
     * Render menu.
     *
     * @return string
     */
    public function render()
    {
        $output = '';

        foreach ($this->getItems() as $item) {
            if (!empty($item['additionalRoutes'])) {
                $route[] = $item['route'];
                $route = array_merge($route, $item['additionalRoutes']);
            } else {
                $route = $item['route'];
            }

            $output .= '<li' . ($this->isLinkActive($route) ? ' class="active"' : '') . '>';
            $output .= '<a href="' . route($item['route']) . '">' . $item['icon'] . ' ' . $item['name'] . '</a>';
            $output .= '</li>';
        }

        return $output;
    }

    /**
     * Check whether link for given route is currently selected one.
     *
     * @param mixed $route Route name, array of routes or URL
     * @return bool
     */
    protected function isLinkActive($route)
    {
        if (is_array($route)) {
            return in_array(Route::currentRouteName(), $route);
        }
        if (Route::currentRouteName() == $route) {
            return true;
        }
        if (strpos(URL::current(), $route)) {
            return true;
        }

        return false;
    }
}