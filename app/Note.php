<?php

namespace Codice;

use Auth;
use Codice\Exceptions\NoteNotFoundException;
use Codice\Reminder;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\CommonMarkConverter;

class Note extends Model
{
    /**
     * Attributes appended to the model representation, which are
     * not database columns.
     *
     * @var array
     */
    protected $appends = [
        'content_raw',
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
     * @param  $id Note ID
     * @return Codice\Note
     * @throws Codice\Exceptions\NoteNotFoundException
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
     * Mutator for content attribute, which automagically parses Markdown
     *
     * @param  string $content
     * @return string
     */
    public function getContentAttribute($content)
    {
        $converter = new CommonMarkConverter();
        return $converter->convertToHtml($content);
    }

    /**
     * Returns unparsed Markdown.
     *
     * @return string
     */
    public function getContentRawAttribute($content)
    {
        return $this->attributes['content'];
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
     *
     */
    public function labels()
    {
        return $this->belongsToMany('Codice\Label');
    }

    /**
     * Returns note reminder of given type.
     *
     * @param  int $type
     * @return Codice\Reminder
     */
    public function reminder($type)
    {
        return Reminder::where('note_id', $this->id)->where('type', $type)->logged()->first();
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
