<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractController;
use App\Http\Requests\AlertCreateRequest;
use App\Http\Resources\RecordResource;
use App\Services\RecordService;

class RecordController extends AbstractController
{
    protected $createRequest = AlertCreateRequest::class;
    protected $resource = RecordResource::class;

    /**
     * @var RecordService
     */
    protected $service;


    public function __construct(RecordService $service)
    {
        $this->service = $service;
    }
}
