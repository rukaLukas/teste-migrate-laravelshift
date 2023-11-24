<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Resources\OccupationResource;
use App\Services\OccupationService;

class OccupationController extends AbstractController
{
    protected $resource = OccupationResource::class;

    /**
     * @var OccupationService
     */
    protected $service;

    public function __construct(OccupationService $service)
    {
        $this->service = $service;
    }
}
