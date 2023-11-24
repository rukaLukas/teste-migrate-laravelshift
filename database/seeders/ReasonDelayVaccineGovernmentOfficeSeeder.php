<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\TargetPublic;
use Illuminate\Database\Seeder;
use App\Models\GovernmentOffice;
use App\Models\ReasonDelayVaccine;
use App\Models\TypeReasonDelayVaccine;

class ReasonDelayVaccineGovernmentOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {                                
        $reasonsDelayVaccine = ReasonDelayVaccine::where('is_forwarding', true)->get();
        $governmentOffices = GovernmentOffice::where('type', '1')->where('county_id', '!=', null)
            ->orWhere('type', '2')->where('county_id', '!=', null)
            ->get();        
        foreach ($reasonsDelayVaccine as $reasonDelayVaccine) {
            $reasonDelayVaccine->pivot = new \App\Models\ReasonDelayVaccineGovernmentOffice();            
            foreach ($governmentOffices as $governmentOffice) {
                $reasonDelayVaccine->pivot->insert([
                    'government_office_id' => $governmentOffice->id,
                    'reason_delay_vaccine_id' => $reasonDelayVaccine->id
                ]);               
            }         
        }        
    }
}
