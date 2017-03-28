<?php

namespace Codice;

use Codice\Plugins\Action;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    public static function boot()
    {
        self::mapEvents(self::getModelName());

        parent::boot();
    }

    protected static function mapEvents($model)
    {
        self::creating(function ($object) use ($model) {
            Action::call("$model.creating", [$model => $object]);
        });

        self::created(function ($object) use ($model) {
            Action::call("$model.created", [$model => $object]);
        });

        self::updating(function ($object) use ($model) {
            Action::call("$model.updated", [$model => $object]);
        });

        self::deleting(function ($object) use ($model) {
            Action::call("$model.deleting", [$model => $object]);
        });

        self::deleted(function ($object) use ($model) {
            Action::call("$model.deleted", [$model => $object]);
        });

        self::saving(function ($object) use ($model) {
            Action::call("$model.saving", [$model => $object]);
        });

        self::saved(function ($object) use ($model) {
            Action::call("$model.saved", [$model => $object]);
        });
    }

    protected static function getModelName()
    {
        return strtolower(str_replace('\\', '', snake_case(class_basename(static::class))));
    }
}
