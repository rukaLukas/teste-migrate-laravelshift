<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Requests\Configuration\DeadlineRequest;
use App\Http\Resources\Configuration\DeadlineResource;
use App\Services\Configuration\DeadlineService;

class DeadlineController extends AbstractController
{
    protected $createRequest = DeadlineRequest::class;
    protected $resource = DeadlineResource::class;

    /**
     * @var DeadlineService
     */
    protected $service;

    public function __construct(DeadlineService $service)
    {
        $this->service = $service;
    }
}
