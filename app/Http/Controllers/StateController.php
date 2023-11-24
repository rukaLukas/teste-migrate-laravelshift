<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractController;
use App\Services\CountyService;
use App\Services\StateService;

class StateController extends AbstractController
{
     /**
     * @var CountyService
     */
    protected $service;

    public function __construct(StateService $service)
    {
        $this->service = $service;
    }
}
