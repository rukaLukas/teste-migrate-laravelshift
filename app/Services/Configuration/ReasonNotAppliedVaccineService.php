<?php
namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\ReasonNotAppliedVaccineRepository;

class ReasonNotAppliedVaccineService extends AbstractService
{
    /**
     * @var ReasonNotAppliedVaccineRepository
     */
    protected $repository;

    public function __construct(ReasonNotAppliedVaccineRepository $repository)
    {
        $this->repository = $repository;
    }
}
