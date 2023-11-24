<?php
namespace App\Services;


use App\Abstracts\AbstractService;
use App\Infra\Repository\VaccineScheduledAlertRepository;

class VaccineScheduledAlertService extends AbstractService
{
    /**
     * @var VaccineScheduledRepository
     */
    protected $repository;

    public function __construct(VaccineScheduledAlertRepository $repository)
    {
        $this->repository = $repository;
    }
}
