<?php

namespace App\Console\Commands;

use App\Models\Accession;
use Illuminate\Console\Command;

class Deploy060 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:060';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */    
    public function handle()
    {
        // limpa registros antihos de go_rdv e popula com dados corretos        
        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE go_rdv');

        $accessions = Accession::whereIn('status', ['aprovado', 'aprovado_automaticamente'])
        ->where('status_prefeito', 'confirmado')
        ->where('status_gestor_politico', 'confirmado')->get();
        try {
            foreach($accessions as $accession) {                           
                $reasonsDelayVaccine = \App\Models\ReasonDelayVaccine::where('is_forwarding', true)->get();
                $governmentOffices = \App\Models\GovernmentOffice::where('county_id', $accession->county_id)->get();                   
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
            $this->info("update go_rdv com sucesso");

            // call the migration
            $this->call('migrate', ['--path' => './database/migrations/0.6.0']);
            $this->info("migrations 0.6.0 com sucesso");

            // deleta registros duplicados
            \Illuminate\Support\Facades\DB::statement('DELETE FROM type_status_vaccinations WHERE id > 4');

            \Illuminate\Support\Facades\DB::statement('DELETE t1
                                                        FROM government_offices t1
                                                        JOIN government_offices t2 ON t1.id > t2.id 
                                                        AND t1.name = t2.name
                                                        AND t1.county_id = t2.county_id');
            $this->info("limpou registros duplicados");
            
            // call seeders
            $this->call('db:seed', ['--class' => 'MigrateSeeder']);
            $this->info("executado migrateSeeder com sucesso");
            $this->call('db:seed', ['--class' => 'UnderSubGroupSeeder']);
            $this->info("executado UnderSubGroupSeeder com sucesso");
            $this->call('db:seed', ['--class' => 'TypeReasonDelayVaccineSeeder']);  
            $this->info("executado TypeReasonDelayVaccineSeeder com sucesso");          

        } catch (\Throwable $th) {
            $this->info("Não foi possível prosseguir" . $th->getMessage());
            return;
        }                
        
        $this->info("configuracoes deploy 0.6.0 com sucesso");
        
        return;
    }
}
