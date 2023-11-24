<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Alert;
use App\Models\Breed;
use App\Models\Genre;
use App\Helper\Number;
use App\Models\County;
use App\Models\Vaccine;
use App\Models\Accession;
use App\Models\TypeAlerts;
use App\Models\TargetPublic;
use App\Models\VaccineCardPicture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeStatusVaccination;
use App\Models\VaccineScheduledAlert;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccessionFactory extends Factory
{    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {              
        return [
            'county_id' => County::inRandomOrder()->first()->id,
            'prefeito_id' => User::inRandomOrder()->first()->id,
            'gestor_politico_id' => User::inRandomOrder()->first()->id,
            'status_prefeito' => Accession::STATUS['APROVADO'],
            'status_gestor_politico' => Accession::STATUS['APROVADO'],
            'status' => Accession::STATUS['APROVADO']
        ];  
    }
}
