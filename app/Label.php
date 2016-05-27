<?php

namespace Codice;

use Auth;
use Codice\Exceptions\LabelNotFoundException;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
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
     * Find label owned by currently logged user.
     *
     * @param  int $id Label ID
     * @return \Codice\Label
     * @throws \Codice\Exceptions\LabelNotFoundException
     */
    public static function findOwned($id)
    {
        $label = self::logged()->find($id);

        if (!$label) {
            throw new LabelNotFoundException;
        }

        return $label;
    }

    /**
     * Fall back to default if provided label color doesn't exist.
     *
     * @param  int $color Color selected by user
     * @return int
     */
    public static function ensureColorIsValid($color)
    {
        if (!in_array($color, array_keys(config('labels.colors'))))  {
            $color = 1;
        }

        return $color;
    }

    /**
     * Notes that belong to the label.
     */
    public function notes()
    {
        return $this->belongsToMany('Codice\Note');
    }

    /**
     * Set query scope to currently logged user.
     *
     * @param $query \Illuminate\Database\Query\Builder
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeLogged($query)
    {
        return $query->where('user_id', '=', Auth::id());
    }

    /**
     * User owning the label.
     */
    public function user()
    {
        return $this->belongsTo('Codice\User');
    }
}
