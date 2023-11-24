<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Alert;
use App\Models\Breed;
use App\Models\Genre;
use App\Helper\Number;
use App\Models\AlertStep;
use App\Models\Record;
use App\Models\StatusAlert;
use App\Models\Vaccine;
use App\Models\TypeAlerts;
use App\Models\TargetPublic;
use App\Models\VaccineCardPicture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeStatusVaccination;
use App\Models\VaccineScheduledAlert;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {       
        return [
            'record_id' => Record::inRandomOrder()->first()->id,            
            'status_alert_id' => AlertStep::inRandomOrder()->first()->id,            
            'user_id' => User::inRandomOrder()->first()->id,            
        ];          
    }
}
