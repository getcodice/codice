<?php

namespace Codice;

use Auth;
use Codice\Note;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    const TYPE_EMAIL = 1;
    const TYPE_SMSAPI = 2;

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

    public static function addReminder(Note $note, $remindAt, $type)
    {
        if ($type === self::TYPE_EMAIL) {
            $content = $note->content;
        } elseif ($type === self::TYPE_SMSAPI) {
            $content = substr(strip_tags($note->content_raw), 0, 60);
        }

        if ($type === self::TYPE_SMSAPI) {
            // Send API request
        }

        return self::create([
            'user_id' => Auth::id(),
            'note_id' => $note->id,
            'data' => [],
            'remind_at' => $remindAt,
            'type' => $type,
        ]);
    }

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
