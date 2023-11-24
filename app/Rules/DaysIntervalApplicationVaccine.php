<?php
namespace App\Rules;

use App\Models\Alert;
use App\Models\Vaccine;
use Illuminate\Support\Carbon;
use App\Models\VaccineScheduledAlert;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class DaysIntervalApplicationVaccine implements Rule, DataAwareRule, ValidatorAwareRule
{

    protected $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

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
        $index = explode('.', $attribute)[1];
        $vaccineId = $this->data[$this->key][$index]['vaccine_id'];        
        $vaccine = Vaccine::findByUUID($vaccineId);
        $alertId = Alert::findByUUID($this->data['alert_id'])->id;
        
        $schemasDoseUnica = ['Dose única', 'Anual', 'Dose inicial'];
        // this vaccine has more that one application to be completed
        if (!in_array($vaccine->schema, $schemasDoseUnica)) {              
            // get only numer from schema
            $schema = preg_replace('/[^0-9]/', '', $vaccine->schema);
            // get the number of doses
            $dose = (int)$vaccine->dose;
            if($dose != 1) {
                // get the days interval from previous vaccine dose
                $previousVaccine = Vaccine::where('name', $vaccine->name)
                ->where('dose', --$dose)->first();                
              
                $daysInterval = $previousVaccine->days_interval;                
                $previousVaccineApplicationDate = VaccineScheduledAlert::where('vaccine_id', $previousVaccine->id)
                    ->where('alert_id', $alertId)->orderByDesc('id')->first();
                
                if (is_null($previousVaccineApplicationDate)) return true;    
                
                $previousVaccineApplicationDate->previous_application;                
                // the next application date from the previous application date + days interval
                $nextApplicationDate = date('Y-m-d', strtotime($previousVaccineApplicationDate->previous_application . ' + ' . $daysInterval . ' days'));                

                $nextApplicationDate = Carbon::create($nextApplicationDate);
                $currentDateApplication = Carbon::create($value);
                
                return $nextApplicationDate->lt($currentDateApplication);                                    
            }
        }    
        
        return true;       
    }   

    public function getAttribute()
    {
        return __('validation.attributes.date_application');
    }

    public function message()
    {
        return 'Data de aplicação antes do prazo mínimo de aplicação entre as doses.';
    }
}