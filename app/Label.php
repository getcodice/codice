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
     * @param  $id Label ID
     * @return Codice\Label
     * @throws Codice\Exceptions\NoteNotFoundException
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
     * Notes that belong to the label.
     *
     */
    public function notes()
    {
        return $this->belongsToMany('Codice\Note');
    }

    /**
     * Set query scope to currently logged user.
     *
     */
    public function scopeLogged($query)
    {
        return $query->where('user_id', '=', Auth::id());
    }

    /**
     * Relation to the User model.
     *
     */
    public function user()
    {
        return $this->belongsTo('Codice\User');
    }
}
