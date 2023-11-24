<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Abstracts\AbstractModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Deadline extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid','name','days','county_id'];
    protected $guarded = ['id'];
    public $timestamps = true;

    const ALERTA = 'Alerta';
    const ANALISE_TECNICA = 'Análise Técnica';
    const ENCAMINHAMENTOS = 'Encaminhamentos';
    const SALA_DE_VACINA = 'Sala de vacina';

    protected static function booted()
    {
        static::addGlobalScope('countyScope', function (Builder $builder) {
            if (Auth::user() && Auth::user()->county_id) {
                $builder->where('county_id', Auth::user()->county_id);
                return $builder;
            }
        });
    }
}

