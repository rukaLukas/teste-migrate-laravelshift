<?php
namespace App\Services\Configuration;

use App\Abstracts\AbstractService;
use App\Infra\Repository\Configuration\GroupUserRepository;
use App\Infra\Repository\Configuration\UnderSubGroupUserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class UnderSubGroupUserService extends AbstractService
{
    /**
     * @var GroupUserRepository
     */
    protected $repository;

    private $underSubGroupService;

    private $userService;

    public function __construct(
        UnderSubGroupUserRepository $repository,
        UnderSubGroupService $underSubGroupService,
        UserService $userService
    )
    {
        $this->repository = $repository;
        $this->underSubGroupService = $underSubGroupService;
        $this->userService = $userService;
    }

    public function beforeSave(Request $request): array
    {
        $params = $request->all();

        $user = $this->userService->find($params['user_id']);
        $underSubGroup = $this->underSubGroupService->find($params['under_sub_group_id']);

        return [
            'under_sub_group_id' => $underSubGroup->id,
            'user_id' => $user->id
        ];
    }
}
