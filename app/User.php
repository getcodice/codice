<?php

namespace Codice;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * Options and their defaults for every new user.
     *
     * @var array
     */
    public static $defaultOptions = [
        'language' => 'pl',
        'notes_per_page' => 15,
        'phone' => '',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'options'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Add welcome note for given user.
     *
     * @param  bool $withLabel Whether to create "Important" label for this note. Used for adding this note for the first time.
     * @return Note
     */
    public function addWelcomeNote($withLabel = true)
    {
        $note = new Note;
        $note->user_id = $this->id;
        $note->content = file_get_contents(base_path('resources/lang/' . $this->options['language'] . '/welcome.md'));
        $note->expires_at = Carbon::tomorrow();
        $note->status = 1;
        $note->save();

        if ($withLabel) {
            $label = new Label;
            $label->user_id = $this->id;
            $label->name = trans('install.welcome-note-label');
            $label->color = 6;
            $label->save();

            $note->labels()->attach($label);
        }

        return $note;
    }
}
