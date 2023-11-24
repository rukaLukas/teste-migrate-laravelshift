<?php
namespace App\Http\Requests\Alert\step;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\ReasonNotAppliedVaccine;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\DaysIntervalApplicationVaccine;
use App\Rules\UniqueVaccineScheduledAlert;

class VaccineRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        return Auth::user()->hasAccess('vaccine-room');        
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
        $validator = Validator::make(Request::all(), [ 
            'complete' => 'required|boolean', // true - Aplicação total de vacina, false - Aplicação parcial de vacina'   
            'alert_id' => 'required|uuid|exists:alerts,uuid', 
            'reason_not_applied_vaccine_id' => 'required_if:complete,false|uuid|exists:reason_not_applied_vaccines,uuid',
            'applied_vaccines.*.vaccine_id' => [
                'required',
                'uuid',
                'exists:vaccines,uuid',
                new UniqueVaccineScheduledAlert                
            ],            
            'applied_vaccines.*.date_application' => [
                'required',
                'date',
                'before_or_equal:today',
                new DaysIntervalApplicationVaccine('applied_vaccines')     
            ],
            'not_applied_vaccines.*.vaccine_id' => [
                'required_if:complete,false',
                'uuid',
                'exists:vaccines,uuid',
                // new UniqueVaccineScheduledAlert                
            ],           
        ]);        

        $validator->sometimes('comments', 'required|string|min:5|max:255', function ($input) {
            $ReasonNotAppliedVaccine = ReasonNotAppliedVaccine::where('uuid', $input['reason_not_applied_vaccine_id'])
                ->where('description', 'like', '%Outro%')
                ->first();            

            return !is_null($ReasonNotAppliedVaccine);         
        });

        return $validator->getRules();
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'reason_not_applied_vaccine_id' => 'Motivo da não aplicação da vacina',
            'vaccine_id' => 'Vacina',
            'alert_id' => 'Alerta',
            'date_application' => 'Data da aplicação',                   
        ];
    }

    public function messages()
    {        
        return [
            'applied_vaccines.*.date_application.before_or_equal' => 'Data da aplicação, deve ser uma data anterior ou igual a hoje',
            'reason_not_applied_vaccine_id.required_if' => 'Motivo da não aplicação da vacina é obrigatório quando a aplicação não é total',     
        ];
    }   
}
