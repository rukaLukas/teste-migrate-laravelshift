<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Requests\Configuration\SubGroupUserRequest;
use App\Services\Configuration\GroupService;
use App\Services\Configuration\SubGroupUserService;

class SubGroupUserController extends AbstractController
{
    protected $createRequest = SubGroupUserRequest::class;

    /**
     * @var SubGroupUserService
     */
    protected $service;

    public function __construct(SubGroupUserService $service)
    {
        $this->service = $service;
    }
}
