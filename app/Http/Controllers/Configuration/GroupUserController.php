<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Requests\Configuration\GroupUserRequest;
use App\Services\Configuration\GroupService;
use App\Services\Configuration\GroupUserService;

class GroupUserController extends AbstractController
{
    protected $createRequest = GroupUserRequest::class;

    /**
     * @var GroupService
     */
    protected $service;

    public function __construct(GroupUserService $service)
    {
        $this->service = $service;
    }
}
