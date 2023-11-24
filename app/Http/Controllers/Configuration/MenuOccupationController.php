<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Resources\Configuration\MenuOccupationResource;
use App\Services\Configuration\MenuCountyService;
use App\Services\Configuration\MenuOccupationService;

class MenuOccupationController extends AbstractController
{
    protected $resource = MenuOccupationResource::class;

    /**
     * @var MenuCountyService
     */
    protected $service;

    public function __construct(MenuOccupationService $service)
    {
        $this->service = $service;
    }
}
