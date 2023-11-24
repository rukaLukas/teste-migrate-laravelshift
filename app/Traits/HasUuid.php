<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $collection = collect($model->fillable);
            if ($collection->contains('uuid')) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * @param $uuid
     *
     * @return mixed
     */
    public static function findByUUID(string $uuid)
    {
        return static::where('uuid', '=', $uuid)->first();
    }
}
