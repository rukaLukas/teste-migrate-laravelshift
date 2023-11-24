<?php
namespace App\Services\Alert;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Alert\PendingAlertRepository;

class PendingAlertService extends AbstractService
{
    /**
     * @var PendingAlertRepository
     */
    protected $repository;

    public function __construct(PendingAlertRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * list function
     *
     * @param int $userId
     * @return mixed
     */
    public function list(int $userId): mixed
    { 
        return $this->repository->listConsolided($userId);
    }

    /**
     * search function
     *
     * @param int $userId
     * @param string $search
     * @return mixed
     */
    public function search(int $userId, string $search): mixed
    {
        return $this->repository->search($userId, $search);
    }
}
