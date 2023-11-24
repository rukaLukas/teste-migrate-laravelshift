<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;

class MenuOccupation extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'name', 'menu_id', 'occupation_id'];
    protected $guarded = ['id'];
    public $timestamps = true;


    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function scopeQuery(Builder $queryBuilder, $params = []): Builder
    {
        if (Arr::has($params, 'occupation_id')) {
            $params['occupation_id'] = Occupation::where(
                ['uuid' => $params['occupation_id']]
            )->first()->id;
            return parent::scopeQuery($queryBuilder, $params);
        }
        return $queryBuilder;
    }

}
