<?php

namespace Codice;

use Codice\Support\Traits\Owned;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use Owned;

    /**
     * Allow all attributes to be mass assigned.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Disable automagically handled (created|updated)_at columns.
     */
    public $timestamps = false;

    /**
     * Notes that belong to the label.
     */
    public function notes()
    {
        return $this->belongsToMany('Codice\Note');
    }

    /**
     * Setter for label color, with simple sanitization.
     *
     * @param  string $color
     * @return void
     */
    public function setColorAttribute($color)
    {
        $this->attributes['color'] = in_array($color, array_keys(config('labels.colors'))) ? $color : 1;
    }
}
