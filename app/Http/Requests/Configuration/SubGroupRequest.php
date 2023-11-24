<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class SubGroupRequest extends FormRequest
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
            'name' => 'required',
            'group_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name' => 'Nome é obrigatório.',
            'group_id' => 'Uma Sub Região depende de uma Região.',
        ];
    }
}
