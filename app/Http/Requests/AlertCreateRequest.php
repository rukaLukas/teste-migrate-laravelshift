<?php

namespace App\Http\Requests;

use App\Models\Record;
use App\Models\Vaccine;
use App\Models\TargetPublic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeStatusVaccination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AlertCreateRequest extends FormRequest
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

    public function messages()
    {
        $dateChild = $this->getDateChild();
        return [
            'type_status_vaccination_id.*' => 'Informe a situação vacinal',
            'mother_name.required' => 'Informe os dados de um dos responsáveis(mãe ou pai)',
            'birthdate.after_or_equal' => 'A data de nascimento não corresponde a de uma criança, deve ser posterior a ' . $dateChild,
            'birthdate.before_or_equal' => 'A data de nascimento inválida, deve ser anterior a ' . $dateChild,
            'birthdate.before' => 'A data de nascimento inválida, deve ser anterior a data atual',
            // 'reasons_delay_vaccine.prohibited' => 'O campo Motivo do Atraso da Vacinação só deve ser informado quando o status da vacinação não for "Em dia"',
            'reasons_delay_vaccine.array' => 'O campo Motivo do Atraso da Vacinação só deve ser informado quando o status da vacinação não for "Em dia"',
            'reasons_delay_vaccine.required' => 'O campo Motivo do Atraso da Vacinação é obrigatório para a situação vacinal informada',
            // 'reasons_delay_vaccine' => 'O campo motivo do atraso vacinal só existe quando vacinação não está em atraso',
            'vaccine_scheduled_alerts.*.previous_application.before' => 'A data de aplicação da vacina não pode ser posterior a data atual',
        ];
    }

    private function validate() {

        $record = !is_null(Request::get('record_id')) ? Record::findByUUID(Request::get('record_id')) : null;
        $recordIdValidation = 'nullable|uuid|exists:records,uuid';
        $cpf = "";
        $suscard = "";
        if (!is_null($record)) {
            $cpf = $record->cpf;
            $suscard = $record->suscard;
            // $recordIdValidation .= !empty($cpf) ? ',cpf,' . Request::get('cpf') : '';
            // $recordIdValidation .= !empty($suscard) ? ',suscard,' . Request::get('suscard') : '';
        }

        $validator = Validator::make(Request::all(), [
            'record_id' => $recordIdValidation, // se existe o campo record_id, então o suscard e cpf informado deve corresponder ao record_id
            'type_status_vaccination_id' => 'required|uuid|exists:type_status_vaccinations,uuid',
            'vaccine_room_id' => 'nullable|uuid|exists:under_sub_groups,uuid',
            'vaccine_scheduled_alerts' => 'nullable|array|min:1',
            'vaccine_scheduled_alerts.*.vaccine_id' => 'uuid|exists:vaccines,uuid',
            'vaccine_scheduled_alerts.*.previous_application' => 'date|before:today',
            // 'vaccine_scheduled_alerts.*.next_application' => 'date',
            'target_public_id' => 'required|uuid|exists:target_publics,uuid',
            'name' => 'required|min:5',
            'email' => 'nullable|email',
            'mobilephone' => 'nullable|size:11',
            'phone' => 'nullable|size:10',
            // 'cpf' => 'nullable|required_if:suscard,null|cpf|unique:records,cpf',
            // 'suscard' => 'nullable|required_if:cpf,null|required_unless:record_id,null|size:8',  // se cpf for nulo e record_id não for igual a null, então suscard é obrigatório
            'rg' => 'nullable|digits_between:7,11',
            'birthdate' => 'required|date|before:today' . $this->getDateBirthDateValidation(),
            'breed_id' => 'required|int',
            'genre_id' => 'required|int',
            'mother_email' => 'nullable|email',
            'mother_cpf' => 'nullable|cpf',
            // 'mother_rg' => 'required|digits_between:7,11',
            'mother_phone' => 'nullable|size:10',
            'mother_mobilephone' => 'nullable|size:11',
            'father_name' => 'nullable|min:5',
            'father_email' => 'nullable|email',
            'father_cpf' => 'nullable|cpf',
            'father_phone' => 'nullable|size:10',
            'father_mobilephone' => 'nullable|size:11',
            'postalcode' => 'required|min:8',
            'street' => 'required|min:5',
            'state' => 'required|size:2',
            'city' => 'required',
            'district' => 'required',
            'reason_not_has_vac_card_pic' => 'nullable|int',
            'vaccine_card_pictures' => 'nullable|array|min:1',
            'visit_date' => 'date',
            'bae' => 'nullable|int',
            'county_id' => 'required|int|exists:counties,id',
        ]);

        // $validator->sometimes('mother_name', 'required|min:5', function ($input) { // registro novo
        //     return (strlen($input['mother_name']) < 6 && strlen($input['father_name']) < 6);
        // });

        $validator->sometimes('cpf', 'cpf|unique:records,cpf', function ($input) { // registro novo
            return (is_null($input['suscard']) && is_null($input['record_id'])) || (!is_null($input['cpf']) && is_null($input['record_id']));
        });

        $validator->sometimes('cpf', 'nullable|cpf|unique:records,cpf', function ($input) use ($cpf) { // registro existente, nova visita/alerta
            return empty($cpf) && !is_null($input['record_id']);
        });

        $validator->sometimes('suscard', 'size:15|unique:records,suscard', function ($input) { // registro novo
            return (is_null($input['cpf']) && is_null($input['record_id'])) || (!is_null($input['suscard']) && is_null($input['record_id']));
        });

        $validator->sometimes('suscard', 'nullable|size:15|unique:records,suscard', function ($input) use ($suscard) { // registro existente, nova visita/alerta
            return empty($suscard) && !is_null($input['record_id']);
        });

        $validator->sometimes('reasons_delay_vaccine', 'array|max:0', function ($input) {
            $typeStatusVaccination = $this->getTypeStatusVaccinationId();
            return !is_null($typeStatusVaccination) ? $typeStatusVaccination->name == 'Vacinação em dia' : false;
        });

        $validator->sometimes('reasons_delay_vaccine', 'required|array|min:1', function ($input) {
            $typeStatusVaccination = $this->getTypeStatusVaccinationId();
            return !is_null($typeStatusVaccination) ? $typeStatusVaccination->name != 'Vacinação em dia' : false;
        });

        return $validator->getRules();
    }

    private function getTypeStatusVaccinationId()
    {
        // dd(TypeStatusVaccination::findByUUID(Request::get('type_status_vaccination_id')));
        // $x = !is_null(Request::get('type_status_vaccination_id')) ? TypeStatusVaccination::findByUUID(Request::get('type_status_vaccination_id')) : null;
        return !is_null(Request::get('type_status_vaccination_id')) ? TypeStatusVaccination::findByUUID(Request::get('type_status_vaccination_id')) : null;
    }

    private function getDateChild(string $format = 'Y-m-d')
    {
        $date = new Carbon();
        return $date->subYears(env('AGE_CHILD', 14))->format($format);
    }

    private function getDateBirthDateValidation(): string
    {
        $dateBirthDateValidation = "";
        $targetPublicId = Request::get('target_public_id');
        $targetPublicId = !is_null(TargetPublic::findByUUID($targetPublicId)) ? TargetPublic::findByUUID($targetPublicId)->name : null;
        if (!is_null($targetPublicId)) {
            $dateChild = $this->getDateChild();
            $dateBirthDateValidation = $targetPublicId == 'Criança' ? '|after_or_equal:' . $dateChild : '|before_or_equal:' . $dateChild;
        }

        return $dateBirthDateValidation;
    }
}