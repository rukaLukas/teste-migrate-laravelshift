<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Services\AccessionService;
use App\Services\UserService;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Accession extends AbstractModel
{
    use HasFactory, HasUuid, SoftDeletes;

    const STATUS = [
        'APROVADO' => 'aprovado',
        'REPROVADO' => 'reprovado',
        'PENDENTE' => 'pendente',
        'ATIVO' => 'ativo',
        'CONFIRMADO' => 'confirmado',
        'APROVADO_AUTOMATICAMENTE' => 'aprovado_automaticamente',
    ];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'uuid',
        'county_id',
        'prefeito_id',
        'gestor_politico_id',
        'status',
        'status_prefeito',
        'status_gestor_politico'
    ];

    protected $appends = [
        'pendencies',
        'created_at_to_human',
        'created_at_formated',
    ];

    protected $guarded = ['id'];

    public $timestamps = true;

    public function getPendenciesAttribute()
    {
        $serviceAccession = app()->make(AccessionService::class);
        $pendencies = $serviceAccession->getPendencies($this);
        return empty($pendencies) ? ' - - - - - - ' : $pendencies;
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    /**
     * @param Builder $queryBuilder
     * @param array $params
     * @return Builder
     */
    public function scopeQuery(Builder $queryBuilder, $params = []): Builder
    {
        $queryBuilder = parent::scopeQuery($queryBuilder, $params);
        if (isset($params['searchAllFields'])) {
            $queryBuilder->orWhereHas('county', function (Builder $query) use ($params) {
                $query->where('name', 'like', '%' . strtolower($params['searchAllFields']) . '%');
            });
            $queryBuilder->orWhereHas('county.state', function (Builder $query) use ($params) {
                $query->where('name', 'like', '%' . strtolower($params['searchAllFields']) . '%')
                    ->orWhere('sigla', 'like', '%' . strtolower($params['searchAllFields']) . '%');
            });
        }
        $user = app()->make(UserService::class)->find(Auth::user()->getAuthIdentifier());
        if ($user->occupation_id === Occupation::ARTICULADOR_MUNICIPAL) {
            $queryBuilder->orWhere('county_id', '=', Auth::user()->county_id);
        }
        return $queryBuilder;
    }
}
