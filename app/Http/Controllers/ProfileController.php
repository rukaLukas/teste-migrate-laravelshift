<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProfileService;
use App\Abstracts\AbstractController;
use App\Http\Resources\ProfileResource;
use App\Http\Requests\ProfileCreateRequest;

class ProfileController extends AbstractController
{
    protected $createRequest = ProfileCreateRequest::class;
    protected $resource = ProfileResource::class;

    /**
     * @var ProfileService
     */
    protected $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }
}
