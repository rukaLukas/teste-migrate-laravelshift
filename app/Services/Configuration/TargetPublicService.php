<?php
namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\TargetPublicRepository;

class TargetPublicService extends AbstractService
{
    /**
     * @var TargetPublicRepository
     */
    protected $repository;

    public function __construct(TargetPublicRepository $repository)
    {
        $this->repository = $repository;
    }
}
