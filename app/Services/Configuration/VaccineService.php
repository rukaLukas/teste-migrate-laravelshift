<?php
namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\VaccineRepository;

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
