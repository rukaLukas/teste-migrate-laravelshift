<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Helper\Number;
use App\Models\Occupation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserDeleteRequest extends FormRequest
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
        return [
            'id' => [
                'required',
                'uuid',
                'exists:users,uuid',
                Rule::exists('users', 'uuid')->where(function ($query) {
                    $query->whereNotIn('occupation_id',                    
                    Occupation::whereIn('name', ['Gestor nacional', 'Prefeito', 'Gestor(a) político(a)'])->pluck('id')->toArray()
                    );
                }),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $path = explode('/', Request::path());
        $id = end($path);        
        $this->merge([
            'id' => $id,
        ]);
    }

    public function messages()
    {
        return [
            'id.exists' => 'Não permitido exclusão de usuários do tipo super administrador, prefeito e gestor político.',
        ];
    }
}
