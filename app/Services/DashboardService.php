<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Infra\Repository\DashboardRepository;

class DashboardService extends AbstractService
{
    /**
     * @var DashboardRepository
     */
    protected $repository;

    public function __construct(DashboardRepository $repository)
    {
        parent::__construct($repository);
        $this->repository = $repository;
    }
}
