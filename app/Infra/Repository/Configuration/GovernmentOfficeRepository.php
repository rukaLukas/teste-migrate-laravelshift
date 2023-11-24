<?php

namespace App\Infra\Repository\Configuration;

use App\Models\User;
use App\Models\GovernmentOffice;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class GovernmentOfficeRepository extends AbstractRepository
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct(GovernmentOffice $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        // dd($this->getAttribute($params, 'name'), $this->getAttribute($params, 'county_id'));
        return [
            'name' => $this->getAttribute($params, 'name'),
            'email' => $this->getAttribute($params, 'email'),
            'county_id' => $this->getAttribute($params, 'county_id'),
            'type' => $this->getAttribute($params, 'type'),           
        ];
    }

    public function getByCounty(string $id): mixed
    {        
        return $this->getModel()->where('county_id', $id)->get();
    }    

    // public function getPaginationList(array $params, array $with = [], int $perPage = null)
    // {
    //     return parent::getPaginationList($params, ['governmentOfficeUsers']);
    // }
}
