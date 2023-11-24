<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\TargetPublic;
use Illuminate\Database\Seeder;
use App\Models\GovernmentOffice;
use App\Models\ReasonDelayVaccine;
use App\Models\TypeReasonDelayVaccine;

class ReasonDelayVaccineTargetPublicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {                        
        // Insert target publics related
        $targetPublics = TargetPublic::where('name', 'Criança')
            ->orWhere('name', 'Adolescente')
            ->orWhere('name', 'Gestante')
            ->get();    
        $reasonsDelayVaccines = ReasonDelayVaccine::all();   
        foreach ($reasonsDelayVaccines as $reasonDelayVaccine) {
            $reasonDelayVaccine->pivot = new \App\Models\ReasonDelayVaccineTargetPublic();            
            foreach ($targetPublics as $targetPublic) {
                $reasonDelayVaccine->pivot->insert([
                    'target_public_id' => $targetPublic->id,
                    'reason_delay_vaccine_id' => $reasonDelayVaccine->id
                ]);               
            }         
        }
    }
}
