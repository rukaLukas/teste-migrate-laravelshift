<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Requests\Configuration\SubGroupRequest;
use App\Http\Resources\Configuration\SubGroupResource;
use App\Services\Configuration\SubGroupService;

class SubGroupController extends AbstractController
{
    protected $createRequest = SubGroupRequest::class;
    protected $resource = SubGroupResource::class;

    /**
     * @var SubGroupService
     */
    protected $service;

    public function __construct(SubGroupService $service)
    {
        $this->service = $service;
    }

    public function getUsers(int|string $id)
    {
        return $this->ok(
            $this
                ->service
                ->getRepository()
                ->getModel()
                ->with(['subGroupUsers', 'subGroupUsers.user' => function($q) {
                    $q->orderBy('name', 'desc')->get();
                }
                ])
                ->where(['uuid' => $id])
                ->first()
        );
    }

}
