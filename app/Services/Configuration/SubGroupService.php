<?php

namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\SubGroupRepository;
use Illuminate\Http\Request;

class SubGroupService extends AbstractService
{
    /**
     * @var SubGroupRepository
     */
    protected $repository;

    /**
     * @var GroupService
     */
    protected $groupService;

    public function __construct(SubGroupRepository $repository, GroupService $groupService)
    {
        $this->repository = $repository;
        $this->groupService = $groupService;
    }

    public function beforeSave(Request $request): array
    {
        $params = $request->all();
        $params['group_id'] = $this->groupService->find($params['group_id'])->id;
        return $params;
    }

    public function beforeUpdate(Request $request, int|string $id, string $validationName = null): array
    {
        $params = $request->all();
        $entity = $this->find($id);
        $params['group_id'] = $entity->group_id;
        return $params;
    }

    public function beforeDelete(int|string $id): void
    {
        $subGroup = $this->find($id);

        if ($subGroup->underSubGroups()->get()->count() >= 1) {
            throw new \Exception('Existem salas de vacina vinculadas a essa sub-região.', 422);
        }

        if ($subGroup->subGroupUsers()->get()->count() >= 1) {
            throw new \Exception('Existem usuários vinculados a essa sub-região.', 422);
        }

        parent::beforeDelete($id); // TODO: Change the autogenerated stub
    }
}