<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Requests\Configuration\UnderSubGroupUserRequest;
use App\Services\Configuration\UnderSubGroupUserService;

class UnderSubGroupUserController extends AbstractController
{
    protected $createRequest = UnderSubGroupUserRequest::class;

    /**
     * @var UnderSubGroupUserService
     */
    protected $service;

    public function __construct(UnderSubGroupUserService $service)
    {
        $this->service = $service;
    }
}
