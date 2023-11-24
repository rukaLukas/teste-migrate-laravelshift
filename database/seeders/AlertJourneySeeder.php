<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Alert;
use App\Models\Breed;
use App\Models\Genre;
use App\Models\Record;
use App\Models\StatusAlert;
use App\Models\Vaccine;
use Illuminate\Support\Str;
use App\Models\TargetPublic;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\VaccineCardPicture;
use Illuminate\Support\Facades\Hash;
use App\Models\TypeStatusVaccination;
use App\Models\VaccineScheduledAlert;

class AlertJourneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // call AlertSeeder
        $steps = [
            StatusAlertSeeder::class,
            AlertSeeder::class,
            AlertStepSeeder::class
        ];

        $this->call($steps);        
    }

}
