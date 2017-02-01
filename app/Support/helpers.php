<?php

use Carbon\Carbon;
use Codice\Label;
//use DB;
use Illuminate\Support\HtmlString;

function boolean_select_options()
{
    return [
        '1' => trans('app.form.yes'),
        '0' => trans('app.form.no')
    ];
}

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

function escape_like($string) {
    // @todo: PDO's quote doesn't serve its purpose here - what should we do?
    $string = addcslashes($string, '%_');

    return $string;
}

function icon($icon)
{
    return '<span class="fa fa-' . $icon . '"></span>';
}

function pad_zero($int)
{
    return $int < 10 ? '0'.$int : (string) $int;
}

function form_picker($labelTranslationKey, $inputName, $value = null)
{
?>
    <div class="form-group form-picker" id="<?= $inputName ?>_picker" data-wrap="true">
        <label class="control-label sr-only" for="<?= trans($inputName) ?>"><?= trans($labelTranslationKey) ?></label>
        <div class="input-group">
            <input type="datetime"
                   data-input
                   name="<?= trans($inputName) ?>"
                   id="<?= trans($inputName) ?>"
                   class="form-control"
                   value="<?= $value ?>"
                   placeholder="<?= datetime_placeholder($labelTranslationKey) ?>">
            <span class="input-group-addon" data-toggle><?= icon('calendar') ?></span>
        </div>
    </div>
<?php
}

function note_creation_date(Carbon $date)
{
    return $date->diffInDays() > 7 ? $date->format(trans('app.date')) : $date->diffForHumans();
}

function quickform(array $options = [])
{
    $options = array_merge([
        'expires_at' => null,
        'label' => null,
        'labels' => Label::mine()->orderBy('name')->pluck('name', 'id'),
        'target_url' => route('index'),
    ], $options);

    $html = View::make('note.quickform', [
        'options' => $options,
    ]);

    return new HtmlString($html);
}
