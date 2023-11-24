<?php

namespace App\Infra\Repository\Configuration;

use App\Models\User;
use App\Models\Occupation;
use App\Models\GovernmentOffice;
use App\Models\GovernmentOfficeUser;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GovernmentOfficeUserRepository extends AbstractRepository
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct(GovernmentOfficeUser $model)
    {
        $this->model = $model;
    }

    public function getByUser(string $id)
    {       
        return $this->model->where('user_id', User::findByUUID($id)->id)->paginate()->withQueryString();        
    }

    public function formatParams(array $params): array
    {
        return [
            'user_id' => $this->getAttribute($params, 'user_id'),
            'government_office_id' => $this->getAttribute($params, 'government_office_id'),
        ];
    }

    public function getUsersIn(GovernmentOffice $governmentOffice)
    {
        $governmentOfficeUsers = $this->getGovernementOfficeUsers($governmentOffice);
        $idsUsers = $this->getIdsUsers($governmentOfficeUsers);
        return User::whereIn('id', $idsUsers)->get(['name', 'email', 'uuid']);
    }

    public function getUsersOut(GovernmentOffice $governmentOffice)
    {                
        $governmentOfficeUsers = $this->getGovernementOfficeUsers($governmentOffice);     
        
        $idsUsers = $this->getIdsUsers($governmentOfficeUsers);
        $usersOutFiltered = [];        
        User::whereNotIn('id', $idsUsers)
            ->where('county_id', $governmentOffice->county_id)            
            ->whereNotIn('occupation_id', [
                Occupation::GESTOR_NACIONAL, 
                Occupation::GESTOR_POLITICO,
                Occupation::TECNICO_VERIFICADOR,
                Occupation::PREFEITO
            ])                        
            ->get()
            ->each(function($user) use (&$usersOutFiltered) {
                if ($user->governmentOffices->count() == 0) {
                    $usersOutFiltered[] = new User([
                        'name' => $user->name,
                        'email' => $user->email,
                        'uuid' => $user->uuid
                    ]);                         
                }                
            });                        
            
        $usersOutFiltered = Collection::make($usersOutFiltered);               
        
        return $usersOutFiltered;
    }

    private function getGovernementOfficeUsers(GovernmentOffice $governmentOffice)
    {        
        return $this
            ->model
            ->with(['user'])
            ->where("government_office_id" , $governmentOffice->id)
            ->get();
    }

    private function getIdsUsers($governmentOfficeUsers)
    {        
        return array_map(function($gov) {
            return $gov['user_id'];
        }, $governmentOfficeUsers->toArray());
    }
}
