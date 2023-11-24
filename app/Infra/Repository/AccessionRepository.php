<?php

namespace App\Infra\Repository;

use App\Models\Accession;
use App\Models\County;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class AccessionRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Accession $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'county_id' => $this->getAttribute($params, 'county_id'),
            'prefeito_id' => $this->getAttribute($params, 'prefeito_id'),
            'gestor_politico_id' => $this->getAttribute($params, 'gestor_politico_id'),
            'status' => $this->getAttribute($params, 'status')
        ];
    }

    public function selo(array $params, string $siglaMunicipio)
    {
        $model = new County();
        return $model
            ->with(['users', 'accessions'])
            ->whereHas('accessions')
            ->whereHas('state', function ($query) use ($siglaMunicipio) {
                return $query->where('sigla', '=', $siglaMunicipio);
            })
            ->query($params)
            ->orderBy('name')
            ->get();
//        $query = $this->model->with(['county', 'county.state'])
//            ->whereHas('county.state', function($query) use ($siglaMunicipio) {
//                return $query->where('sigla', '=', $siglaMunicipio);
//            })
//            ->get();
    }
}
