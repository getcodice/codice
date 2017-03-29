<?php

namespace Codice;

use Carbon\Carbon;
use Codice\Support\Traits\Owned;
use Codice\Support\Traits\Taggable;

class Note extends Model
{
    use Owned, Taggable;

    /**
     * Attributes appended to the model representation, which are
     * not database columns.
     *
     * @var array
     */
    protected $appends = [
        'expires_at_fmt',
        'state',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'expires_at',
        'updated_at'
    ];

    /**
     * Allow all attributes to be mass assigned.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Returns formatted expiration date if it's set, null otherwise.
     *
     * @return null|string
     */
    public function getExpiresAtFmtAttribute()
    {
        if (isset($this->attributes['expires_at'])) {
            return Carbon::parse($this->attributes['expires_at'])
                ->format(trans('app.datetime'));
        }

        return null;
    }

    /**
     * Makes current note state easily available as $note->state
     * @see $this->appends
     */
    public function getStateAttribute()
    {
        $now = time();

        if ($this->attributes['status']) {
            $state = 'success';
        } elseif (!$this->attributes['status']
            && $this->attributes['expires_at']
            && strtotime($this->attributes['expires_at']) > $now
            && strtotime($this->attributes['expires_at']) - $now < 24 * 60 * 60) {
            $state = 'warning';
        } elseif (!$this->attributes['status']
            && $this->attributes['expires_at']
            && strtotime($this->attributes['expires_at']) > $now) {
            $state = 'info';
        } elseif (!$this->attributes['status']
            && $this->attributes['expires_at']
            && strtotime($this->attributes['expires_at']) < $now) {
            $state = 'danger';
        } else {
            $state = 'default';
        }

        return $this->attributes['state'] = $state;
    }

    /**
     * Labels that belong to the note.
     */
    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    /**
     * Returns note reminder of given type.
     *
     * @param  int $type
     * @return \Codice\Reminder
     */
    public function reminder($type)
    {
        return Reminder::where('note_id', $this->id)->where('type', $type)->mine()->first();
    }

    /**
     * Returns all reminders for the note.
     *
     * @return \Codice\Reminder
     */
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Saves a note but without bumping updated_at property.
     *
     * @param  array $options
     * @return bool
     */
    public function saveWithoutTouching(array $options = [])
    {
        $this->timestamps = false;
        $result = parent::save($options);
        $this->timestamps = true;

        return $result;
    }
}
