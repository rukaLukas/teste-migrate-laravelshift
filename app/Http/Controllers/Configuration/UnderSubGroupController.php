<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Exceptions\GeneralException;
use App\Http\Requests\Configuration\UnderSubGroupRequest;
use App\Http\Resources\Configuration\UnderSubGroupResource;
use App\Services\Configuration\UnderSubGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UnderSubGroupController extends AbstractController
{
    protected $createRequest = UnderSubGroupRequest::class;
    protected $resource = UnderSubGroupResource::class;

    /**
     * @var UnderSubGroupService
     */
    protected $service;

    public function __construct(UnderSubGroupService $service)
    {        
        $this->service = $service;
    }

    public function move(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->ok($this->service->move($request->all()));
            DB::commit();
            return $this->success($this->messageSuccessDefault);
        } catch (\Exception | ValidationException | GeneralException $e) {
            DB::rollBack();
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, $e->getMessage());
            }
        }
    }

    public function getUsers(int|string $id)
    {        
        return $this->ok(
            $this
                ->service
                ->getRepository()
                ->getModel()
                ->with(['underSubGroupUsers', 'underSubGroupUsers.user' => function($q) {
                    $q->orderBy('name', 'desc')->get();
                }
                ])
                ->where(['uuid' => $id])
                ->first()
        );
    }
}
