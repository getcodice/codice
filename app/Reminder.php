<?php

namespace Codice;

use Codice\Support\Traits\Owned;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use Owned;

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
}
