<?php
namespace App\Services;


use App\Abstracts\AbstractService;
use App\Infra\Repository\RecordRepository;

class RecordService extends AbstractService
{
    protected $repository;

    public function __construct(RecordRepository $repository)
    {
        $this->repository = $repository;
    }
}
