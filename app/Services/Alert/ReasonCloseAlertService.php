<?php
namespace App\Services\Alert;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Alert\ReasonCloseAlertRepository;

class ReasonCloseAlertService extends AbstractService
{
    /**
     * @var ReasonCloseAlertRepository
     */
    protected $repository;

    public function __construct(ReasonCloseAlertRepository $repository)
    {
        $this->repository = $repository;
    }
}
