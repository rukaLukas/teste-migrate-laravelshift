<?php

namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\DeadlineRepository;
use App\Infra\Repository\Configuration\MenuRepository;

class MenuService extends AbstractService
{
    /**
     * @var DeadlineRepository
     */
    protected $repository;

    public function __construct(MenuRepository $repository)
    {
        $this->repository = $repository;
    }
}
