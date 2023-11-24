<?php

namespace App\Http\Controllers;

use JsonSerializable;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Mensagem padrão do sistema SUCCESS
     * @var string
     */
    protected $messageSuccessDefault = 'Operação realizada com com sucesso';

    /**
     * Mensagem padrão do sistema ERROR
     * @var string
     */
    protected $messageErrorDefault = 'Ops';

    /**
     * @param null $message
     * @param null $items
     * @param int $status
     * @return JsonResponse
     */
    public function error(
        $message = null,
        $items = null,
        int $status = Response::HTTP_UNPROCESSABLE_ENTITY,
        \Exception|null $exception = null
    ): JsonResponse
    {
        if (is_null($message)) {
            $message = $this->messageErrorDefault;
        }

        $data = ['status' => 'error', 'message' => $message];

        if ($exception && env('APP_ENV') === 'local') {
            array_push($data, ['exception' => $exception->getTrace()]);
        }

        if (is_array($items)) {
            foreach ($items as $key => $item) {
                $data['errors'][$key] = $item;
            }
        }

        return response()->json($data, $status);
    }

    /**
     * @param $message
     * @param null $items
     * @param int $status
     * @return JsonResponse
     */
    public function success(
        $message = null,
        $items = null,
        int $status = Response::HTTP_OK
    ): JsonResponse
    {
        if (is_null($message)) {
            $message = $this->messageSuccessDefault;
        }

        $data = ['status' => $status, 'message' => $message, 'type' => 'success'];

        if ($items instanceof Arrayable) {
            $items = $items->toArray();
            foreach ($items as $key => $item) {
                $data['response'][$key] = $item;
            }
        }

        if ($items instanceof JsonResource) {
            $data['response'] = $items;
        }

        return response()->json(['data' => $data], $status);
    }

    /**
     * @param array|ResourceCollection $items
     * @param int $status
     * @return JsonResponse
     */
    public function ok(
        array|ResourceCollection|LengthAwarePaginator|JsonSerializable $items = [],
        int $status = Response::HTTP_OK
    ): JsonResponse
    {
        $formatData = ($items instanceof ResourceCollection) ? $items->resource : $items;

        $data = [
            'type' => 'success',
            'status' => $status,
            'data' => $formatData,
            'show' => false
        ];

        return response()->json($data, $status);
    }
}
