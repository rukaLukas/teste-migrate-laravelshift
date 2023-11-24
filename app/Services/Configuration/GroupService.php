<?php

namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\GroupRepository;
use App\Infra\Repository\Configuration\GroupUserRepository;
use App\Infra\Repository\Configuration\SubGroupRepository;
use App\Infra\Repository\Configuration\SubGroupUserRepository;
use App\Infra\Repository\Configuration\UnderSubGroupRepository;
use App\Infra\Repository\Configuration\UnderSubGroupUserRepository;
use App\Services\CountyService;
use App\Services\UserService;
use Illuminate\Http\Request;

class GroupService extends AbstractService
{
    /**
     * @var GroupRepository
     */
    protected $repository;

    protected $countyService;

    public function __construct(
        GroupRepository $repository,
        CountyService   $countyService
    )
    {
        $this->repository = $repository;
        $this->countyService = $countyService;
    }

    public function afterSave(Request $request, mixed $model): void
    {
        $subGroupService = app()->make(SubGroupService::class);

        $params['name'] = 'Sub Região';
        $params['group_id'] = $model->id;
        $subGroupService->getRepository()->save($params);
    }

    public function beforeUpdate(Request $request, int|string $id, string $validationName = null): array
    {
        return array_merge($request->all(), ['id' => $id, 'county_id' => $this->entity->county_id]);
    }

    public function move($params)
    {
        if ($params['type'] === 'regional') {
            $this->moveRegional($params);
        } elseif ($params['type'] === 'sub-regional') {
            $this->moveSubRegional($params);
        } else {
            $this->moveUnderSubRegional($params);
        }
    }

    public function removerUsers($field, $repo, $repoUser, $params)
    {
        $model = $repo->find($params['model']);
        foreach ($params['checkboxUsers'] as $user) {
            $repoUser->deleteWhere(['user_id' => $user['id'], $field => $model->id]);
        }
    }

    public function insertUsers($params)
    {
        $typeModel = $this->decideWhichModelToMove($params);

        $userService = app()->make(UserService::class);

        if ($typeModel === 'underSubGroup' || $typeModel === 'under_sub_group') {
            $field = 'under_sub_group_id';
            $repo = app()->make(UnderSubGroupRepository::class);
            $repoUser = app()->make(UnderSubGroupUserRepository::class);
        } elseif ($typeModel === 'subGroup' || $typeModel === 'sub_group') {
            $field = 'sub_group_id';
            $repo = app()->make(SubGroupRepository::class);
            $repoUser = app()->make(SubGroupUserRepository::class);
        } else {
            $field = 'group_id';
            $repo = app()->make(GroupRepository::class);
            $repoUser = app()->make(GroupUserRepository::class);
        }

        $model = isset($params[$typeModel]['id']) && !empty($params[$typeModel]['id']) ? $params[$typeModel]['id'] : $params[$typeModel];

        if (!$model) {
            return;
        }

        $modelObject = $repo->find($model);

        foreach ($params['checkboxUsers'] as $user) {
            $paramsMove['user_id'] = $userService->find($user['id'])->id;
            $paramsMove[$field] = $modelObject->id;
            $repoUser->save($paramsMove);
        }
    }

    public function moveRegional($params)
    {
        $modelRepository = app()->make(GroupRepository::class);
        $modelUserRepository = app()->make(GroupUserRepository::class);
        $this->removerUsers(
            'group_id',
            $modelRepository,
            $modelUserRepository,
            $params
        );
        $this->insertUsers($params);
    }

    public function moveSubRegional($params)
    {
        $modelRepository = app()->make(SubGroupRepository::class);
        $modelUserRepository = app()->make(SubGroupUserRepository::class);
        $this->removerUsers(
            'sub_group_id',
            $modelRepository,
            $modelUserRepository,
            $params
        );
        $this->insertUsers($params);
    }

    public function moveUnderSubRegional($params)
    {
        $modelRepository = app()->make(UnderSubGroupRepository::class);
        $modelUserRepository = app()->make(UnderSubGroupUserRepository::class);
        $this->removerUsers(
            'under_sub_group_id',
            $modelRepository,
            $modelUserRepository,
            $params
        );
        $this->insertUsers($params);
    }

    public function decideWhichModelToMove($params): string
    {
        if (isset($params['underSubGroup']) && !empty($params['underSubGroup'])) {
            return 'underSubGroup';
        }

        if (isset($params['under_sub_group']) && !empty($params['under_sub_group'])) {
            return 'under_sub_group';
        }

        if (isset($params['subGroup']) && !empty($params['subGroup'])) {
            return 'subGroup';
        }

        if (isset($params['sub_group']) && !empty($params['sub_group'])) {
            return 'sub_group';
        }

        return 'group';
    }

    public function getByCounty($countyId): array
    {
        $county = $this->countyService->find($countyId);
        $groups = $county->groups()->get()->pluck('name', 'uuid');
        return generateSelectOption($groups);
    }

    public function beforeDelete(int|string $id): void
    {
        $group = $this->find($id);

        if ($group->groupUsers()->count() >= 1) {
            throw new \Exception('Existem usuários vinculadas a essa região.', 422);
        }
        if ($group->subGroups()->count() >= 1) {
            throw new \Exception('Existem sub-regiões vinculadas a essa região.', 422);
        }

        parent::beforeDelete($id); // TODO: Change the autogenerated stub
    }
}
