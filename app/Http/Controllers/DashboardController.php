<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractController;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends AbstractController
{
    /**
     * @var DashboardService
     */
    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function totalAlerts(): JsonResponse
    {
        $total = rand(20, 90);
        return $this->ok(['total_alerts' => $total]);
    }
}
