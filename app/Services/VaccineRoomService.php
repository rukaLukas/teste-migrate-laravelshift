<?php
namespace App\Services;


use App\Abstracts\AbstractService;
use App\Infra\Repository\VaccineRoomRepository;

class VaccineRoomService extends AbstractService
{
    /**
     * @var VaccineRoomRepository
     */
    protected $repository;

    public function __construct(VaccineRoomRepository $repository)
    {
        $this->repository = $repository;
    }
}
