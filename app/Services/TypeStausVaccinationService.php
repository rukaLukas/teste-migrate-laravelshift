<?php
namespace App\Services;


use App\Abstracts\AbstractService;
use App\Infra\Repository\TypeStatusVaccineRepository;

class TypeStausVaccinationService extends AbstractService
{
    /**
     * @var TypeStatusVaccineRepository
     */
    protected $repository;

    public function __construct(TypeStatusVaccineRepository $repository)
    {
        $this->repository = $repository;
    }
}
