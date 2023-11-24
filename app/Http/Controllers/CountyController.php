<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractController;
use App\Services\CountyService;

class CountyController extends AbstractController
{
     /**
     * @var CountyService
     */
    protected $service;

    public function __construct(CountyService $service)
    {
        $this->service = $service;
    }

    public function getByState($stateId)
    {
        $preRequisite = $this->service->preRequisite($stateId);
        return $this->ok(compact('preRequisite'));
    }
}
