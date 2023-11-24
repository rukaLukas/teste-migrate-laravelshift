<?php
namespace App\Services\Configuration;

use App\Models\Occupation;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Abstracts\AbstractService;
use App\Exceptions\NaoEncontradaException;
use App\Infra\Repository\Configuration\GovernmentOfficeRepository;

class GovernmentOfficeService extends AbstractService
{
    /**
     * @var GovernmentOfficeRepository
     */
    protected $repository;

    /**
     * @var GovernmentOfficeUserService
     */
    protected $governmentOfficeUserService;

    /**
     * @var UserService
     */
    protected $userService;

    public function __construct(
        GovernmentOfficeRepository $repository,
        GovernmentOfficeUserService $governmentOfficeUserService,
        UserService $userService
    )
    {
        $this->repository = $repository;
        $this->governmentOfficeUserService = $governmentOfficeUserService;
        $this->userService = $userService;
    }

    public function beforeSave(Request $request): array
    {   
        return $request->all()['form'];        
    }

    public function afterSave(Request $request, mixed $model): void
    {
        $id = $model->id;
        $governmentOffice = $this->find($id);
        $this->governmentOfficeUserService->getRepository()->deleteWhere(['government_office_id' => $governmentOffice->id]);

        $governementOfficeUser = [
            'government_office_id' => $governmentOffice->id
        ];

        $usersId = $request->all()['userIn'];        

        foreach ($usersId as $userId) {
            $governementOfficeUser['user_id'] = $this->userService->find($userId)->id;           
            $this->governmentOfficeUserService->getRepository()->save($governementOfficeUser);
        }
    }

    public function beforeUpdate(Request $request, int|string $id, string $validationName = null): array
    {
        $governmentOffice = $this->find($id);
        $this->governmentOfficeUserService->getRepository()->deleteWhere(['government_office_id' => $governmentOffice->id]);

        $governementOfficeUser = [
            'government_office_id' => $governmentOffice->id
        ];

        $usersId = $request->all()['userIn'];

        foreach ($usersId as $userId) {
            $governementOfficeUser['user_id'] = $this->userService->find($userId)->id;
            $this->governmentOfficeUserService->getRepository()->save($governementOfficeUser);
        }

        $returnData = $request->all()['form'];
        $returnData['county_id'] = $governmentOffice->county_id;
        $returnData['type'] = $governmentOffice->type;
        
        return $returnData;        
    }

    /**
     * @return mixed
     */
    public function preRequisite($id = null)
    {                        
        $governementOffice = $this->find($id);    
        if (is_null($governementOffice)){            
            $users = $this->userService->findByCounty($id);         
            $usersOut = $this->filterUsersOut($users);

            $arr['users_in'] = []; 
            $arr['users_out'] = $usersOut;            
           return $arr;
        }
        
        $arr['users_in'] = $this->governmentOfficeUserService->getRepository()->getUsersIn($governementOffice);                        
        $arr['users_out'] = $this->governmentOfficeUserService->getRepository()->getUsersOut($governementOffice);        
        
        return $arr;
    }

    /**
     * getByCounty function
     *
     * @param [type] $id
     * @return void
     */
    public function getByCounty($id)
    {                
        $governmentOffices = $this->repository->getByCounty($id);
        
        throw_if(
            !$governmentOffices,
            new NaoEncontradaException()
        );

        return $governmentOffices;
    }

    /**
     * default function
     *
     * @return void
     */
    public function getDefault()
    {                
        $governmentOffices = $this->repository->where(array('county_id' => null));
        
        throw_if(
            !$governmentOffices,
            new NaoEncontradaException()
        );

        return $governmentOffices;
    }

    /**
     * filterUsersOut function
     *
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @return mixed
     */
    private function filterUsersOut(\Illuminate\Database\Eloquent\Collection $users): mixed
    {
        $usersOut = [];
        foreach ($users as $user) {
            if (
                $user->governmentOffices->count() == 0 &&
                $user->occupation_id != Occupation::GESTOR_NACIONAL && 
                $user->occupation_id != Occupation::GESTOR_POLITICO &&
                $user->occupation_id != Occupation::TECNICO_VERIFICADOR &&
                $user->occupation_id != Occupation::PREFEITO
            ) {
                $usersOut[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'uuid' => $user->uuid,                   
                ];
            }
        }
      
        return $usersOut;
    }
}
