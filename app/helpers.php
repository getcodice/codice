<?php

function datetime_placeholder($placehoderTranslationKey)
{
    return trans($placehoderTranslationKey) . ' (' . trans('app.datetime-human') . ')';
}

/**
 * Prints a link for navbar with "active" class set if needed.
 */
function navbar_link($route, $icon, $transKey)
{
    $out = '<li' . (is_link_active($route) ? ' class="active"' : '') . '><a href="';
    $out .= route($route) . '">' . icon($icon) . ' ' . trans('app.menu.' . $transKey) . '</a>';
    return $out;
}

function icon($icon)
{
    return '<span class="fa fa-' . $icon . '"></span>';
}

function is_link_active($route)
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
