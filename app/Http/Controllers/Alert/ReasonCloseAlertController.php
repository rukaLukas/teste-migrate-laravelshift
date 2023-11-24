<?php
namespace App\Http\Controllers\Alert;

use App\Abstracts\AbstractController;
use App\Services\Alert\ReasonCloseAlertService;

class ReasonCloseAlertController extends AbstractController
{
     /**
     * @var ReasonCloseAlertService
     */
    protected $service;

    public function __construct(ReasonCloseAlertService $service)
    {
        $this->service = $service;
    }
}
