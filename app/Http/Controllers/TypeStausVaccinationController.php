<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractController;
use App\Services\TypeStausVaccinationService;

class TypeStausVaccinationController extends AbstractController
{
     /**
     * @var TypeStausVaccinationService
     */
    protected $service;

    public function __construct(TypeStausVaccinationService $service)
    {
        $this->service = $service;
    }
}
