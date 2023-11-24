<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class GroupUserRequest extends FormRequest
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
            'group_id' => 'required',
            'user_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'group_id' => 'Região é obrigatório',
            'user_id' => 'Usuário é obrigatório',
        ];
    }
}
