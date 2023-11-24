<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Helper\Number;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\UniqueUserByCpf;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Rules\UniqueUserByEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $path = explode('/', Request::path());
        $id = end($path);

        return [
            'pronoun' => 'required|uuid|exists:App\Models\Pronoun,uuid',
            'email' => [
                'required',
                'email',
                new UniqueUserByEmail
            ],
            'name' => 'required|min:10',
            'birthdate' => 'required|date_format:Y-m-d|before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
            'cpf' => [
                'required',
                'cpf',
                new UniqueUserByCpf
            ],
            'cell_phone' => 'required|min:14',
            'office_phone' => 'required|min:11',
            'occupation' => 'required|uuid|exists:App\Models\Occupation,uuid',
            'county_id' => 'required|exists:App\Models\County,id',
        ];
    }  

    public function attributes()
    {
        return [
            'county_id.exists' => 'Município não encontrado',
            'birthdate' => 'Data de nascimento'
        ];
    }
}
