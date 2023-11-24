<?php

namespace App\Services;


use App\Abstracts\AbstractService;
use App\Infra\Repository\DelayedVaccineRepository;

class DelayedVaccineService extends AbstractService
{
    /**
     * @var DelayedVaccineRepository
     */
    protected $repository;

    public function __construct(DelayedVaccineRepository $repository)
    {
        $this->repository = $repository;
    }
}
