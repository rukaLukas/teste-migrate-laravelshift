<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Requests\Configuration\TargetPublicCreateRequest;
use App\Http\Resources\Configuration\TargetPublicResource;
use App\Services\Configuration\TargetPublicService;

class TargetPublicController extends AbstractController
{
    protected $createRequest = TargetPublicCreateRequest::class;
    protected $resource = TargetPublicResource::class;

    /**
     * @var TargetPublicService
     */
    protected $service;

    public function __construct(TargetPublicService $service)
    {
        $this->service = $service;
    }
}
