<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class GovernmentOfficeCreateRequest extends FormRequest
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
            'form.name' => 'required|string|min:3',
            'form.email' => 'required|email',
            'form.county_id' => 'required|exists:counties,id',
            'userIn' => 'exists:users,uuid', // TODO check if user is in the same county           
        ];
    }

    public function messages()
    {
        return [  
            'form.name' => 'Nome é obrigatório.',
            'form.email' => 'Email é obrigatório.',
            'form.county_id' => 'Não vinculado a nenhum município. Município é obrigatório.',
            'userIn.exists' => 'Usuário selecionado é inválido.',
        ];
    }
}
