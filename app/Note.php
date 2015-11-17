<?php

namespace Codice;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * Attributes appended to the model representation, which are
     * not database columns.
     *
     * @var array
     */
    protected $appends = [
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
        $this->belongsTo('Codice\User');
    }
}
