<?php

namespace Codice;

use Auth;
use Codice\Support\Traits\Owned;

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

    /**
     * {@inheritdoc}
     */
    public function scopeMine($query)
    {
        return $query->leftJoin('notes', 'note_id', '=', 'notes.id')
            ->where('notes.user_id', '=', Auth::id());
    }

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        return $this->belongsTo(Note::class, 'note_id')->getResults()->belongsTo(User::class);
    }
}
