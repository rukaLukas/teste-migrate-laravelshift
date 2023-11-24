<?php

namespace App\Console\Commands;

use App\Models\Accession;
use Illuminate\Console\Command;

class PopulateGoRdv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:go_rdv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate registers to go_rdv table';
    
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
        } catch (\Throwable $th) {
            $this->info("Não foi possível prosseguir registro duplicado - " . $th->getMessage());
            return;
        }                
        
        $this->info("inserido registros para go_rdv");
        
        return;               
    }    
}