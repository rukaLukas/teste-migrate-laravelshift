<?php
namespace App\Services;

use App\Abstracts\AbstractService;
use App\Infra\Repository\AccessionRepository;
use App\Infra\Repository\StateRepository;

class StateService extends AbstractService
{
    /**
     * @var AccessionRepository
     */
    protected $repository;

    public function __construct(StateRepository $repository)
    {
        $this->repository = $repository;
    }
}
