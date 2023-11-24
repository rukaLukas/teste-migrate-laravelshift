<?php
namespace App\Http\Controllers\Alert;

use App\Abstracts\AbstractController;
use App\Services\Alert\ClosedAlertService;

class ClosedAlertController extends AbstractController
{
     /**
     * @var ClosedAlertService
     */
    protected $service;

    public function __construct(ClosedAlertService $service)
    {
        $this->service = $service;
    }
}
