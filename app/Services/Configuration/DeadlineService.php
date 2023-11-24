<?php
namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\DeadlineRepository;
use Illuminate\Http\Request;

class DeadlineService extends AbstractService
{
    /**
     * @var DeadlineRepository
     */
    protected $repository;

    public function __construct(DeadlineRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @param int|string $id
     * @param string|null $validationName
     * @return array
     * @throws \Exception
     */
    public function beforeUpdate(Request $request, int|string $id, string $validationName = null): array
    {
        if ($request->input('days') > 30) {
            throw new \Exception('O limite do prazo são de 30 dias');
        }
        return parent::beforeUpdate($request, $id, $validationName);
    }
}
