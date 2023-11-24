<?php

namespace App\Http\Controllers;

use App\Services\VaccineRoomService;
use App\Abstracts\AbstractController;

class VaccineRoomController extends AbstractController
{
     /**
     * @var VaccineRoomService
     */
    protected $service;

    public function __construct(VaccineRoomService $service)
    {
        $this->service = $service;
    }
}
