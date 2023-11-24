<?php
namespace App\Services;

use App\Abstracts\AbstractService;
use App\Infra\Repository\CountyRepository;

class CountyService extends AbstractService
{
    /**
     * @var CountyRepository
     */
    protected $repository;

    public function __construct(CountyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function preRequisite($id = null)
    {
        $arr = [];
        if ($id) {
            $stateService = app()->make(StateService::class);
            $params = ['state_id' => $stateService->find($id)->id];
            $model = $this->repository->getModel()->where($params)->pluck('name', 'id')->all();
            $arr['counties'] = generateSelectOption($model);
        }
        return $arr;
    }
}
