<?php
namespace App\Services;


use App\Abstracts\AbstractService;
use App\Infra\Repository\VaccineRepository;

class VaccineService extends AbstractService
{
    /**
     * @var VaccineRepository
     */
    protected $repository;

    public function __construct(VaccineRepository $repository)
    {
        $this->repository = $repository;
    }
}
