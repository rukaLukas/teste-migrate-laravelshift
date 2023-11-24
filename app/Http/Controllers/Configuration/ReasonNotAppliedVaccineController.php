<?php
namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Services\Configuration\ReasonNotAppliedVaccineService;

class ReasonNotAppliedVaccineController extends AbstractController
{
     /**
     * @var ReasonNotAppliedVaccineService
     */
    protected $service;

    public function __construct(ReasonNotAppliedVaccineService $service)
    {
        $this->service = $service;
    }
}
