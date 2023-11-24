<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class ReasonsVaccineDelayRequest extends FormRequest
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
            'description' => 'required',
            'to' => 'required',
            'forwarding' => 'required',
            'target_public_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'description' => 'Motivo é obrigatório',
            'para' => 'Para é obrigatório',
            'forwarding' => 'Encaminhamento é obrigatório',
            'target_public_id' => 'Público alvo é obrigatório'
        ];
    }
}
