<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\County;
use Illuminate\Database\Eloquent\Model;

class CountyRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(County $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
        ];
    }

    public function getPaginationList(array $params, array $with = [], int $perPage = null)
    {
        return $this->getModel()->with($with)->query($params)->paginate(9999999)->withQueryString();
    }

    public function doReport(array $params)
    {
        return $this->getModel()->with(['users'])->query($params)->orderBy('name')->get();
    }
}
