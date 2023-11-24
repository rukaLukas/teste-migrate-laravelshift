<?php
namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\GroupUserRepository;
use App\Infra\Repository\Configuration\SubGroupUserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class SubGroupUserService extends AbstractService
{
    /**
     * @var SubGroupUserRepository
     */
    protected $repository;

    private $subGroupService;

    private $userService;

    public function __construct(
        SubGroupUserRepository $repository,
        SubGroupService $subGroupService,
        UserService $userService
    )
    {
        $this->repository = $repository;
        $this->subGroupService = $subGroupService;
        $this->userService = $userService;
    }

    public function beforeSave(Request $request): array
    {
        $params = $request->all();

        $user = $this->userService->find($params['user_id']);
        $subGroup = $this->subGroupService->find($params['sub_group_id']);

        return [
            'sub_group_id' => $subGroup->id,
            'user_id' => $user->id
        ];
    }
}
