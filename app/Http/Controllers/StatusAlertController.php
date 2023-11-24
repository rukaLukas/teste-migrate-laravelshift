<?php

namespace App\Http\Controllers;

use App\Services\StatusAlertService;
use App\Abstracts\AbstractController;
use App\Http\Resources\StatusAlertResource;

class StatusAlertController extends AbstractController
{
    protected $resource = StatusAlertResource::class;
    
    /**
     * @var StatusAlertService
     */
    protected $service;
   
    public function __construct(StatusAlertService $service)
    {
        $this->service = $service;        
    }
}
