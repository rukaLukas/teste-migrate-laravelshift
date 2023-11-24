<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Resources\Configuration\MenuResource;
use App\Services\Configuration\DeadlineService;
use App\Services\Configuration\MenuService;

class MenuController extends AbstractController
{
    protected $resource = MenuResource::class;

    /**
     * @var DeadlineService
     */
    protected $service;

    public function __construct(MenuService $service)
    {
        $this->service = $service;
    }
}
