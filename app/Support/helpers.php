<?php

function calendar_class($day, $month, $events)
{
    $classes = [];

    if ($day->month != $month) {
        $classes[] = 'blur';
    }
    if (isset($events[$day->format('n-j')]['created'])) {
        $classes[] = 'created';
    }
    if (isset($events[$day->format('n-j')]['expiring'])) {
        $classes[] = 'expiring';
    }

    return implode(' ', $classes);
}

function datetime_placeholder($placehoderTranslationKey)
{
    return trans($placehoderTranslationKey) . ' (' . trans('app.datetime-human') . ')';
}

function icon($icon)
{
    return '<span class="fa fa-' . $icon . '"></span>';
}

function pad_zero($int)
{
    return $int < 10 ? '0'.$int : (string) $int;
}

function plugin_path($pluginName = '')
{
    return base_path("plugins/$pluginName");
}
