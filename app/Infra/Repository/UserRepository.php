<?php

namespace App\Infra\Repository;

use App\Models\User;
use App\Helper\Number;
use App\Models\Profile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function existByEmail($email)
    {
        return $this->model->where(['email' => $email])->exists();
    }

    /**
     * @param string $cpf
     * @return mixed
     */
    public function existByCPF(string $cpf)
    {
        return $this->model->where(['cpf' => $cpf])->exists();
    }

    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        return $this->model->where(['email' => $email])->first();
    }

     /**
     * @param string $id
     * @param array $with
     * @return Model
     */
    public function findByCounty(string $id)
    {
        return $this->model->where('county_id', $id)->get();
    }

    public function formatParams(array $params): array
    {        
        $formatted_params = [
            'name' => $this->getAttribute($params, 'name'),
            'email' => $this->getAttribute($params, 'email'),
            'profile_id' => $this->getAttribute($params, 'profile_id', Profile::inRandomOrder()->first()->id),
            'birthdate' => $this->getAttribute($params, 'birthdate'),
            'cpf' => Number::getOnlyNumber($this->getAttribute($params, 'cpf')),
            'cell_phone' => Number::getOnlyNumber($this->getAttribute($params, 'cell_phone')),
            'office_phone' => Number::getOnlyNumber($this->getAttribute($params, 'office_phone')),
            'pronoun_id' => $this->getAttribute($params, 'pronoun_id'),
            'occupation_id' => $this->getAttribute($params, 'occupation_id'),
            'county_id' => $this->getAttribute($params, 'county_id'),
        ];

        if (Arr::has($params, 'password')) {
            $formatted_params['password'] = Hash::make($this->getAttribute($params, 'password'));
        }
        
        return $formatted_params;
    }
}
