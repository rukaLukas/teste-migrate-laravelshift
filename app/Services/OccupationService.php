<?php
namespace App\Services;

use App\Abstracts\AbstractService;
use App\Infra\Repository\OccupationRepository;

class OccupationService extends AbstractService
{
    /**
     * @var OccupationRepository
     */
    protected $repository;

    public function __construct(OccupationRepository $repository)
    {
        $this->repository = $repository;
    }
}
