<?php

namespace App\Services\Alert;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Alert\TechnicalAnalysisRepository;
use App\Services\DelayedVaccineService;
use App\Services\VaccineScheduledAlertService;

class TechnicalAnalysisService extends AbstractService
{
    /**
     * @var TechnicalAnalysisRepository
     */
    protected $repository;

    public function __construct(
        VaccineScheduledAlertService $vaccineScheduledAlertService,
        DelayedVaccineService        $delayedVaccineService
    )
    {
    }
}
