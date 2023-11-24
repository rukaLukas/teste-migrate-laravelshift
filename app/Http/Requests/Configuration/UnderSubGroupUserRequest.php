<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class UnderSubGroupUserRequest extends FormRequest
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
            'under_sub_group_id' => 'required',
            'user_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'under_sub_group_id' => 'Sala de vacina é obrigatório',
            'user_id' => 'Usuário é obrigatório',
        ];
    }
}
