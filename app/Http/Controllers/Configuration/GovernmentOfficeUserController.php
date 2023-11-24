<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use App\Abstracts\AbstractController;
use App\Services\Configuration\GovernmentOfficeUserService;
use App\Http\Resources\Configuration\GovernmentOfficeResource;
use App\Http\Resources\Configuration\GovernmentOfficeUserResource;

class GovernmentOfficeUserController extends AbstractController
{
    protected $resource = GovernmentOfficeUserResource::class;
    protected $updateRequest = GovernmentOfficeUserUpdateRequest::class;
    // protected $resource = GovernmentOfficeResource::class;

    /**
     * @var GovernmentOfficeUserService
     */
    protected $service;

    public function __construct(GovernmentOfficeUserService $service)
    {
        $this->service = $service;
    }

    public function getByUser(Request $request, string $id)
    {        
        //TODO retorno correto deve ser $this->resource::collection($governmentOfficeUser) pois retorna 
        // as informacoes devidamente separadas nas chaves data, links, meta(qual possui informacoes de paginacao)
        $governmentOfficeUser = $this->service->getByUser($id);
        return $this->ok($this->resource::collection($governmentOfficeUser));
        // return $this->resource::collection($governmentOfficeUser);       
    }
}
