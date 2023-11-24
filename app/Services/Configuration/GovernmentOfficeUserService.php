<?php
namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\GovernmentOfficeRepository;
use App\Infra\Repository\Configuration\GovernmentOfficeUserRepository;

class GovernmentOfficeUserService extends AbstractService
{
    /**
     * @var GovernmentOfficeRepository
     */
    protected $repository;

    public function __construct(GovernmentOfficeUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByUser(string $id)
    {                
        return $this->repository->getByUser($id);
    }  
}
