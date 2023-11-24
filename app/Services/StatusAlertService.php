<?php
namespace App\Services;

use App\Abstracts\AbstractService;
use App\Infra\Repository\StatusAlertRepository;

class StatusAlertService extends AbstractService
{
    /**
     * @var StatusAlertRepository
     */
    protected $repository;

    public function __construct(StatusAlertRepository $repository)
    {
        $this->repository = $repository;
    }
}
