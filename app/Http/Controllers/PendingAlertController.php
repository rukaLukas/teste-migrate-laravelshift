<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Abstracts\AbstractController;
use App\Services\Alert\PendingAlertService;
use App\Http\Resources\Alert\PendingAlertResource;

class PendingAlertController extends AbstractController
{
    // protected $createRequest = ProfileCreateRequest::class;
    protected $resource = PendingAlertResource::class;

    /**
     * @var AuthService
     */
    protected $service;

    public function __construct(PendingAlertService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, ...$params): JsonResponse
    {
        try {
            $alerts = $this->service->list($request->id);
            return $this->ok($this->resource::collection($alerts));
        } catch (\Exception $e) {
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, $e->getMessage());
            }
        }
    }

    public function search(Request $request, ...$params): JsonResponse
    {
        try {
            $alerts = $this->service->search($request->id, $request->term);
            return $this->ok($this->resource::collection($alerts));
        } catch (\Exception $e) {
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, $e->getMessage());
            }
        }
    }
}
