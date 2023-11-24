<?php

namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\MenuOccupationRepository;
use App\Services\OccupationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MenuOccupationService extends AbstractService
{
    /**
     * @var MenuOccupationRepository
     */
    protected $repository;
    /**
     * @var MenuService
     */
    private MenuService $menuService;
    /**
     * @var OccupationService
     */
    private OccupationService $occupationService;

    public function __construct(
        MenuOccupationRepository $repository,
        MenuService              $menuService,
        OccupationService        $occupationService
    )
    {
        $this->repository = $repository;
        $this->menuService = $menuService;
        $this->occupationService = $occupationService;

    }

    public function save(Request $request, $validationName = null): Model
    {
        $params = $request->all();
        $paramsFind = [
            'menu_id' => $this->menuService->find($params['menu_id'])->id,
            'occupation_id' => $this->occupationService->find($params['occupation_id'])->id
        ];

        $checkMenuOccupationExists = $this->getRepository()->where($paramsFind)->first();
        if ($checkMenuOccupationExists) {
            $checkMenuOccupationExists->delete();
            return $checkMenuOccupationExists;
        }
        return $this->getRepository()->save($paramsFind);
    }

    public function beforeSave(Request $request): array
    {
        $params = $request->all();
        return [
            'menu_id' => $this->menuService->find($params['menu_id'])->id,
            'occupation_id' => $this->occupationService->find($params['occupation_id'])->id
        ];
    }
}
