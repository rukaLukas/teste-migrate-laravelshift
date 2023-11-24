<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Requests\Configuration\VaccineRequest;
use App\Http\Resources\Configuration\VaccineResource;
use App\Services\Configuration\VaccineService;

class VaccineController extends AbstractController
{
    protected $createRequest = VaccineRequest::class;
    protected $resource = VaccineResource::class;

    /**
     * @var VaccineService
     */
    protected $service;

    public function __construct(VaccineService $service)
    {
        $this->service = $service;
    }
}
