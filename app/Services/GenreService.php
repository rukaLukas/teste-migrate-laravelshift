<?php
namespace App\Services;


use App\Abstracts\AbstractService;
use App\Infra\Repository\GenreRepository;

class GenreService extends AbstractService
{
    /**
     * @var GenreRepository
     */
    protected $repository;

    public function __construct(GenreRepository $repository)
    {
        $this->repository = $repository;
    }
}
