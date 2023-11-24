<?php
namespace App\Rules;

use App\Models\Alert;
use App\Models\Vaccine;
use App\Models\Accession;
use App\Models\VaccineScheduledAlert;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class UniqueVaccineScheduledAlert implements Rule, DataAwareRule, ValidatorAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The validator instance.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
 
        return $this;
    }

    /**
     * Set the current validator.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
 
        return $this;
    }

    public function passes($attribute, $value)
    {
        $this->validator->validated();

        $alert = Alert::findByUUID($this->data['alert_id']);
        $vaccine = Vaccine::findByUUID($value);       
        $alertIds = Alert::where('record_id', $alert->record_id)
                    ->get('id');
        
        foreach ($alertIds as $value) {
            $existsVacSchAlert = VaccineScheduledAlert::where('vaccine_id', $vaccine->id)
                             ->where('alert_id', $value->id)
                             ->first();
            if (!is_null($existsVacSchAlert)) {
                return false;
            }
                
        }
        
        return true;      
    }

    public function message()
    {
        return 'Essa dose de vacina já consta como aplicada.';
    }
}