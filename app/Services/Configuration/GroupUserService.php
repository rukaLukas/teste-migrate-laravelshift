<?php
namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\GroupUserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class GroupUserService extends AbstractService
{
    /**
     * @var GroupUserRepository
     */
    protected $repository;

    private $groupService;

    private $userService;

    public function __construct(
        GroupUserRepository $repository,
        GroupService $groupService,
        UserService $userService
    )
    {
        $this->repository = $repository;
        $this->groupService = $groupService;
        $this->userService = $userService;
    }

    public function beforeSave(Request $request): array
    {
        $params = $request->all();

        $user = $this->userService->find($params['user_id']);
        $group = $this->groupService->find($params['group_id']);

        return [
            'group_id' => $group->id,
            'user_id' => $user->id
        ];
    }
}
