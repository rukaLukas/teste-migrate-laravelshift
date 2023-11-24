<?php
namespace App\Services;

use App\Abstracts\AbstractService;
use App\Infra\Repository\AccessionRepository;
use App\Infra\Repository\RegionRepository;

class RegionService extends AbstractService
{
    /**
     * @var AccessionRepository
     */
    protected $repository;

    public function __construct(RegionRepository $repository)
    {
        $this->repository = $repository;
    }
}
