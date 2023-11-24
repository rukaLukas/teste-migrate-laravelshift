<?php

namespace App\Http\Requests;

use App\Helper\Number;
use Illuminate\Http\Request;
use App\Rules\UniqueUserByCpf;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Rules\UniqueUserByEmail;
use App\Rules\UniqueAccessionByCounty;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AccessionExternalRegisterRequest extends FormRequest
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
        return $this->validate();
    }

    private function validate() {
        $form1 = [
            'form1.state_id' => 'required|uuid|exists:states,uuid',
            'form1.county_id' => [
                'required',
                'int',
                'exists:counties,id',
                new UniqueAccessionByCounty
            ]
        ];

        $form2 = [
            "form2.pronoun" => 'required|uuid|exists:pronouns,uuid',
		    "form2.name" => 'required|min:5|regex:/^[\pL\s]+$/u',
            "form2.cpf" => [
                'required',
                'cpf',
                new UniqueUserByCpf
            ],
		    "form2.birthdate" => 'required|date|before_or_equal:' . (new Carbon())->subYears(18)->format('Y-m-d'),
		    "form2.email" => [
                'required',
                'email',
                new UniqueUserByEmail
            ],
            'form2.occupation' => [
                'required',
                'uuid',
                Rule::exists('occupations', 'uuid')->where(function ($query) {
                    $query->where('name', 'Prefeito');
                }),
            ],
		    "form2.office_phone" => 'required|min:10',
		    "form2.cell_phone" => 'required|min:11'
        ];

        $form3 = [
            "form3.pronoun" => 'required|uuid|exists:pronouns,uuid',
            "form3.name" => 'required|min:5|regex:/^[\pL\s]+$/u',
		    "form3.cpf" => [
                'required',
                'cpf',
                new UniqueUserByCpf
            ],
            "form3.birthdate" => 'required|date|before_or_equal:' . (new Carbon())->subYears(18)->format('Y-m-d'),
		    "form3.email" => [
                'required',
                'email',
                new UniqueUserByEmail,
                'different:form2.email'
            ],
            'form3.occupation' => [
                'required',
                'uuid',
                Rule::exists('occupations', 'uuid')->where(function ($query) {
                    $query->where('name', 'Gestor(a) político(a)');
                }),
            ],
		    "form3.office_phone" => 'required|min:10',
		    "form3.cell_phone" => 'required|min:11'
        ];

        $form = array_merge($form1, $form2, $form3);
        $validator = Validator::make(Request::all(), $form);

        return $validator->getRules();
    }

    protected function prepareForValidation()
    {
        $forms = [
            'form2' => Request::get('form2'),
            'form3' => Request::get('form3')
        ];

        $forms = array_map(function($form) {
            $form['office_phone'] = Number::onlyNumbers($form['office_phone']);
            $form['cell_phone'] = Number::onlyNumbers($form['cell_phone']);
            return $form;
        }, $forms);

        $this->merge($forms);
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        $form1 = [
            'form1.state_id' => 'Estado',
            'form1.county_id' => 'Município',
        ];

        $form2 = [
            "form2.pronoun" => 'Pronome',
            "form2.name" => 'Nome Prefeito',
            "form2.cpf" => 'CPF Prefeito',
            "form2.birthdate" => 'Data de nascimento Prefeito',
            "form2.email" => 'E-mail Prefeito',
            "form2.occupation" => 'Função/Ocupação/Cargo',
            "form2.office_phone" => 'Telefone Prefeito',
            "form2.cell_phone" => 'Celular Prefeito'
        ];

        $form3 = [
            "form3.pronoun" => 'Pronome',
            "form3.name" => 'Nome Gestor Político',
            "form3.cpf" => 'CPF Gestor Político',
            "form3.birthdate" => 'Data de nascimento Gestor Político',
            "form3.email" => 'E-mail Gestor Político',
            "form3.occupation" => 'Função/Ocupação/Cargo',
            "form3.office_phone" => 'Telefone Gestor Político',
            "form3.cell_phone" => 'Celular Gestor Político'
        ];

        return array_merge($form1, $form2, $form3);
    }
}
