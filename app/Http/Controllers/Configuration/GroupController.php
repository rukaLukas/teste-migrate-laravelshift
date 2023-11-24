<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Http\Requests\Configuration\GroupRequest;
use App\Http\Resources\Configuration\GroupResource;
use App\Services\Configuration\GroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends AbstractController
{
    protected $createRequest = GroupRequest::class;
    protected $resource = GroupResource::class;

    /**
     * @var GroupService
     */
    protected $service;

    public function __construct(GroupService $service)
    {
        $this->service = $service;
    }

    public function getUsers(int|string $id)
    {
        $group = $this->find($id);
        return $this->ok(
            $this
                ->service
                ->getRepository()
                ->getModel()
                ->with(['groupUsers', 'groupUsers.user' => function ($q) use ($group) {
                    $q->where('county_id', '=', $group->county_id);
                    $q->orderBy('name', 'desc')->get();
                }
                ])
                ->where(['uuid' => $id])
                ->first()
        );
    }

    public function getByCounty(int|string $countyId)
    {
        return $this->ok($this->service->getByCounty($countyId));
    }

    public function move(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->service->move($request->all());
            DB::commit();
            return $this->success('Usuários movidos com sucesso');
        } catch (\Exception | ValidationException | GeneralException $e) {
            DB::rollBack();
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, $e->getMessage());
            }
        }
    }
}
