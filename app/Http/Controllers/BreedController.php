<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Abstracts\AbstractController;
use App\Services\BreedService;
use App\Http\Resources\BreedResource;

class BreedController extends AbstractController
{
    protected $resource = BreedResource::class;

     /**
     * @var BreedService
     */
    protected $service;

    public function __construct(BreedService $service)
    {
        $this->service = $service;
    }
}
