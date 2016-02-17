<?php

function datetime_placeholder($placehoderTranslationKey)
{
    return trans($placehoderTranslationKey) . ' (' . trans('app.datetime-human') . ')';
}

function icon($icon)
{
    return '<span class="fa fa-' . $icon . '"></span>';
}

function plugin_path($pluginName = '')
{
    return base_path("plugins/$pluginName");
}
