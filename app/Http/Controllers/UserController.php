<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\File;
use App\Abstracts\AbstractController;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\ImageUploadRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    protected $createRequest = UserCreateRequest::class;
    protected $updateRequest = UserUpdateRequest::class;
    protected $deleteRequest = UserDeleteRequest::class;
    protected $resource = UserResource::class;
    protected $validationName = 'User';

    /**
     * @var UserService
     */
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function avatar(ImageUploadRequest $request)
    {
        $this->success($this->service->avatar($request->user(), $request->all()));
    }

    public function getImage($id, $name)
    {
        $path = storage_path('images/users/' . $id . '/' . $name);
        $text = url($path);

        $path = str_replace('/var/www/html/', '/', $text);
        return $path;
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function confirmExternal(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $response = $this->service->confirmExternal($id, $request->all());
            DB::commit();
            return $this->success('Adesão confirmada com sucesso.', $response, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function resendMailAccession(Request $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->service->resendMailAccession($id);
            DB::commit();
            return $this->success('E-mail reenviado com sucesso.', status: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function validateAlreadyMadeAccession($id)
    {
        try {
            DB::beginTransaction();
            $this->service->validateAlreadyMadeAccession($id);
            DB::commit();
            return $this->ok([], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
