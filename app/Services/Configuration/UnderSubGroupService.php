<?php
namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\UnderSubGroupRepository;

class UnderSubGroupService extends AbstractService
{
    /**
     * @var UnderSubGroupRepository
     */
    protected $repository;

    public function __construct(UnderSubGroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function preRequisite($id = null)
    {
        $groupService = app()->make(GroupService::class);
        $arr['groups'] = generateSelectOption($groupService->getRepository()->list());
        return $arr;
    }

    public function move($params)
    {
        $selectedGroup = data_get($params, 'selected_group');
        $selectedSubGroup = data_get($params, 'selected_subGroup');
        $checkBoxUnderSubRegionalToMove = data_get($params, 'checkBoxUnderSubRegionalToMove');


        $serviceUnderSubRegional = app()->make(UnderSubGroupService::class);
        $servicesubGroup = app()->make(SubGroupService::class);

        $subGroup = $servicesubGroup->find($selectedSubGroup);

        foreach ($checkBoxUnderSubRegionalToMove as $underSubRegional) {
            $entityUnderSubRegional = $serviceUnderSubRegional->find($underSubRegional);
            $entityUnderSubRegional->sub_group_id = $subGroup->id;
            $serviceUnderSubRegional->getRepository()->update($entityUnderSubRegional, $entityUnderSubRegional->toArray());
        }

        return $subGroup;
    }
}
