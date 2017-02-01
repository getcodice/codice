<?php

namespace Codice\Support\Traits;

use Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

trait Owned
{
    /**
     * Find owned model (limited to the current user).
     *
     * @param  int $id Model ID
     * @return static
     */
    public static function findMine($id)
    {
        $model = self::mine()->find($id);

        if (!$model) {
            $modelLangFile = array_reverse(explode('\\', get_called_class()))[0];

            throw new HttpResponseException(redirect()->route('index')->with([
                'message' => trans("$modelLangFile.not-found"),
                'message_type' => 'danger',
            ]));
        }

        return $model;
    }

    /**
     * Query scope for getting owned models (limited to the current user).
     *
     * @param $query \Illuminate\Database\Query\Builder
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeMine($query)
    {
        return $query->where('user_id', '=', Auth::id());
    }

    /**
     * Define relationship to the User owning the model.
     */
    public function user()
    {
        return $this->belongsTo('Codice\User');
    }
}
