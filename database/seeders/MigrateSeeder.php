<?php

namespace Database\Seeders;

use App\Models\Vaccine;
use App\Models\Accession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\StatusAlertSeeder;

class MigrateSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $cnes = DB::table('cnes_estabelecimento')
            ->selectRaw('COUNT(*) as total')
            ->first();

        if($cnes->total < 100) {
            $this->call([
                CnesEstabelecimentoSeeder::class,
                UnderSubGroupSeeder::class,
            ]);
        }

        $this->call(StatusAlertSeeder::class);
        $this->call(ReasonNotAppliedVaccineSeeder::class);
        $this->call(TargetPublicReasonDelayVaccineSeeder::class);

        // $this->call([
        //     VaccineSeeder::class,
        // ]);
        // TODO criar um record_id para cada alerta
        
        $accessions = Accession::whereIn('status', ['aprovado', 'aprovado_automaticamente'])
            ->where('status_prefeito', 'confirmado')
            ->where('status_gestor_politico', 'confirmado')->get();
            
        foreach($accessions as $accession) {
            $this->call(DeadlineSeeder::class, false, ['countyId' => $accession->county_id]);                            
        }

        // Atualiza lista de vacinas
        $this->updateVaccines();
    }

    private function updateVaccines()
    {
        $vaccinesToUpdateCondition = [
            [
                'name' => 'Pneumocócica 10 Valente',
                'target_public_id' => 1,
                'schema' => '2 doses',
                'dose' => 1
            ],
            [
                'name' => 'Pneumocócica 10 Valente',
                'target_public_id' => 1,
                'schema' => '2 doses',
                'dose' => 2
            ], 
            [
                'name' => 'Pneumocócica 10 valente',
                'schema' => 'Reforço',
                'target_public_id' => 1,               
            ],  
            [
                'name' => 'Meningocócica C',
                'schema' => 'Reforço',
                'schema' => '2 doses',
                'target_public_id' => 1,               
            ],   
            [
                'name' => 'Meningocócica C',
                'dose' => 1,
                'schema' => '2 doses',
                'target_public_id' => 1,               
            ],
            [
                'name' => 'Meningocócica C',
                'dose' => 2,
                'schema' => '2 doses',
                'target_public_id' => 1,               
            ],   
            [
                'name' => 'Influenza',
                'target_public_id' => 1,               
            ],
            [
                'name' => 'Febre Amarela',
                'target_public_id' => 1,
                'schema' => 'Reforço',
            ],
            [
                'name' => 'Sarampo, Caxumba e Rubéola (SCR)',
                'schema' => 'Dose única',
                'target_public_id' => 1,
            ]            
        ];

        $vaccinesValuesToUpdate = [
            [
                'rules' => 'Até menor de 4 anos',
            ],  
            [
                'rules' => 'Até menor de 4 anos',
            ],  
            [
                'rules' => 'Até menor de 4 anos',
            ],  
            [
                'rules' => 'Até menor de 4 anos',
            ],  
            [
                'rules' => 'Até menor de 4 anos',
            ],
            [
                'rules' => 'Até menor de 4 anos',
            ],  
            [
                'rules' => 'Até menor de 4 anos',
            ],  
            [
                'rules' => 'Até menor de 59 anos',
            ],  
            [
                'rules' => 'Até menor de 59 anos',
            ]     
        ];        

        foreach ($vaccinesToUpdateCondition as $key => $value) {  
            Vaccine::updateOrCreate(                            
                $vaccinesToUpdateCondition[$key],
                $vaccinesValuesToUpdate[$key]           
            );   
        }
    }
}
