<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractController;
use App\Services\DashboardService;
use GuzzleHttp\Client;

class CepController extends AbstractController
{
    /**
     * @var DashboardService
     */
    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function getCep($cep)
    {
        $client = new Client();
        $res = $client->get("https://viacep.com.br/ws/$cep/json/");
        return $res->getBody();
    }
}
