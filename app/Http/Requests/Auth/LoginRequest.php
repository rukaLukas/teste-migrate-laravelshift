<?php

namespace App\Http\Requests\Auth;
use App\Models\Occupation;
use App\Models\User;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class LoginRequest extends FortifyLoginRequest
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
            Fortify::username() => 'required|string|email',
            'password' => 'required',
            'status' => $this->checkStatusUser() ? 'required' : '',
            'status_accession' => $this->checkStatusAccession() ? 'required' : '',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => '',
            'status_accession' => '',
        ]);
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => 'E-mail',
            'password' => 'Senha',
        ];
    }

    public function checkStatusUser()
    {
        $user = User::where('email', $this->email)->first();
        if (!is_null($user) && is_null($user->accessionByCounty)) {
            if ($user->occupation_id !== Occupation::GESTOR_NACIONAL) {
                return true;
            }
        }
    }

    public function checkStatusAccession()
    {
        if (!$this->checkStatusUser()) {
            $user = User::where('email', $this->email)->first();
            if (is_null($user) || $user->occupation_id === Occupation::GESTOR_NACIONAL) return false;

            $status = in_array($user->accessionByCounty->status, ['aprovado_automaticamente', 'aprovado']);
            $statusPrefeitoEGestor = ($user->accessionByCounty->status_prefeito == 'confirmado' && $user->accessionByCounty->status_gestor_politico == 'confirmado');
            return !($status && $statusPrefeitoEGestor);
        }
    }

    public function messages()
    {
        return [
            'status.*' => 'Usuário sem vinculo com município ativo.',
            'status_accession.*' => 'Município possui pendência na situação cadastral.'
        ];
    }
}
