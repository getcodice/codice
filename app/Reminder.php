<?php

namespace Codice;

use Auth;
use Codice\Note;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    public $timestamps = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'remind_at',
    ];

    /**
     * Allow all attributes to be mass assigned.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Relation to the Note
     *
     */
    public function note()
    {
        return $this->belongsTo('Codice\Note');
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
