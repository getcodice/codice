<?php

namespace Codice;

use Auth;
use Carbon\Carbon;
use Codice\Exceptions\NoteNotFoundException;
use Codice\Support\Traits\Taggable;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\CommonMarkConverter;

class Note extends Model
{
    use Taggable;

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
     * Find note owned by currently logged user.
     *
     * @param  int $id Note ID
     * @return \Codice\Note
     * @throws \Codice\Exceptions\NoteNotFoundException
     */
    public static function findOwned($id)
    {
        $note = self::logged()->find($id);

        if (!$note) {
            throw new NoteNotFoundException;
        }

        return $note;
    }

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
        return $this->belongsToMany('Codice\Label');
    }

    /**
     * Returns note reminder of given type.
     *
     * @param  int $type
     * @return \Codice\Reminder
     */
    public function reminder($type)
    {
        return Reminder::where('note_id', $this->id)->where('type', $type)->logged()->first();
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
     * Convert note content from Markdown to HTML.
     *
     * @param  $content string
     * @return string
     */
    public static function toHtml($content)
    {
        $converter = new CommonMarkConverter();
        return $converter->convertToHtml($content);
    }

    /**
     * User owning the note.
     */
    public function user()
    {
        return $this->belongsTo('Codice\User');
    }
}
