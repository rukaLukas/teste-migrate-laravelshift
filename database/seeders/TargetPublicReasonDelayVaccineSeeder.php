<?php

namespace Database\Seeders;

use App\Models\TargetPublic;
use Illuminate\Database\Seeder;
use App\Models\ReasonDelayVaccine;
use App\Models\ReasonDelayVaccineTargetPublic;

class TargetPublicReasonDelayVaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $reasonsDelayVaccine = ReasonDelayVaccine::all();
        $targetPublics = TargetPublic::all();                   
        foreach ($reasonsDelayVaccine as $reasonDelayVaccine) {
            foreach ($targetPublics as $targetPublic) {                                   
                ReasonDelayVaccineTargetPublic::firstOrCreate(
                    [
                        'reason_delay_vaccine_id' => $reasonDelayVaccine['id'],
                        'target_public_id' => $targetPublic['id']
                    ]);
            }                
        }
    }    
}
