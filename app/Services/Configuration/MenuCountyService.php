<?php

namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\DeadlineRepository;
use App\Infra\Repository\Configuration\MenuCountyRepository;
use App\Models\Menu;
use App\Services\CountyService;
use App\Services\StateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MenuCountyService extends AbstractService
{
    /**
     * @var DeadlineRepository
     */
    protected $repository;
    /**
     * @var MenuService
     */
    private MenuService $menuService;
    /**
     * @var CountyService
     */
    private CountyService $countyService;
    /**
     * @var StateService
     */
    private StateService $stateService;

    public function __construct(
        MenuCountyRepository $repository,
        MenuService $menuService,
        CountyService $countyService,
        StateService $stateService
    ) {
        $this->repository = $repository;
        $this->menuService = $menuService;
        $this->countyService = $countyService;
        $this->stateService = $stateService;
    }


    public function save(Request $request, $validationName = null): Model
    {
        $params = $request->all();
        $paramsFind = [
            'menu_id' => $this->menuService->find($params['menu_id'])->id,
            'county_id' => $this->countyService->find($params['county_id'])->id
        ];

        $checkMenuCountyExists = $this->getRepository()->where($paramsFind)->first();
        if ($checkMenuCountyExists) {
            $checkMenuCountyExists->delete();
            return $checkMenuCountyExists;
        }
        return $this->getRepository()->save($paramsFind);
    }

    public function beforeSave(Request $request): array
    {
        $params = $request->all();
        return [
            'menu_id' => $this->menuService->find($params['menu_id'])->id,
            'county_id' => $this->countyService->find($params['county_id'])->id
        ];
    }

    public function addToAllStates(array $params)
    {
        //busca os menus
        $menuCounties = $this->getRepository()->all([]);
        //remove os menus existentes
        array_map(fn($menu) => $this->find($menu['id'])->delete(), $menuCounties->toArray());

        //busca os municipios
        $counties = $this->countyService->getRepository()->all([]);

        //insere os menus aos municipios
        foreach ($params as $menu) {
            $params['menu_id'] = $this->menuService->find($menu)->id;
            foreach ($counties as $county) {
                $params['county_id'] = $county->id;
                $this->getRepository()->save($params);
            }
        }
    }
    public function addToAllCounties(array $params, $stateId)
    {
        //busca os idas dos menus
        foreach ($params as $menu) {
            $idsMenu[] = $this->menuService->find($menu)->id;
        }

        $counties = $this->countyService->getRepository()->all(['state_id' => $stateId])->toArray();
        $idsCounties = array_column($counties,'id');

        $menuCounties = $this
            ->getRepository()
            ->getModel()
            ->whereIn('county_id', $idsCounties)
            ->whereIn('menu_id', $idsMenu)
            ->get();

        array_map(fn($menu) => $this->find($menu['id'])->delete(), $menuCounties->toArray());

        $counties = $this->countyService->getRepository()->all(['state_id' => $stateId]);

        foreach ($params as $menu) {
            $params['menu_id'] = $this->menuService->find($menu)->id;
            foreach ($counties as $county) {
                $params['county_id'] = $county->id;
                $this->getRepository()->save($params);
            }
        }
    }
}
