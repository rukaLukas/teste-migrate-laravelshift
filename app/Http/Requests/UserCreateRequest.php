<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Rules\UniqueUserByCpf;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Rules\UniqueUserByEmail;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'pronoun' => 'required|uuid|exists:App\Models\Pronoun,uuid',
            'email' => [
                'required',
                'email',
                new UniqueUserByEmail
            ],
            'name' => 'required|min:8|regex:/^[\pL\s\-]+$/u',            
            'birthdate' => 'required|date_format:Y-m-d|before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
            'cpf' => [
                'required',
                'cpf',
                new UniqueUserByCpf
            ],
            'cell_phone' => 'required|min:14',
            'office_phone' => 'required|min:11',
            'occupation' => 'required|uuid|exists:App\Models\Occupation,uuid',
            'county_id' => [
                'required',
                'exists:counties,id'                
            ],
            'accession' => Rule::exists('accessions', 'county_id')->where(function ($query) {
                $query->where('county_id', Request::get('county_id'));
                $query->whereIn('status', ['aprovado', 'aprovado_automaticamente']);
                $query->where('status_prefeito', 'confirmado');
                $query->where('status_gestor_politico', 'confirmado');
            }),
        ];
    }

    protected function prepareForValidation()
    {       
        $this->merge([
            'accession' => $this->input('county_id'),
        ]);
    }

    public function messages()
    {
        return [
            'county_id.exists' => 'Município não encontrado',
            'accession.exists' => 'Município informado não possui registro ativo no sistema',            
        ];
    }    
}
