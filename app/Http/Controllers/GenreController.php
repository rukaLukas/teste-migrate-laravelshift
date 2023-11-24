<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractController;
use App\Services\GenreService;
use App\Http\Resources\GenreResource;

class GenreController extends AbstractController
{
     /**
     * @var GenreService
     */
    protected $service;

    public function __construct(GenreService $service)
    {
        $this->service = $service;
    }
}
