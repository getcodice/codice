<?php

namespace Codice;

use Auth;
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
     * Note the reminder belongs to.
     */
    public function note()
    {
        return $this->belongsTo('Codice\Note');
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
     * User owning the reminder.
     */
    public function user()
    {
        return $this->belongsTo('Codice\User');
    }
}
