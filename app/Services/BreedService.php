<?php
namespace App\Services;


use App\Abstracts\AbstractService;
use App\Infra\Repository\BreedRepository;

class BreedService extends AbstractService
{
    /**
     * @var BreedRepository
     */
    protected $repository;

    public function __construct(BreedRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list(int $typeAlert)
    {
        $entity = $this->repository->getModel()->where('type_alert_id', '=', $typeAlert)->get();
        return $entity;
    }
    
}
