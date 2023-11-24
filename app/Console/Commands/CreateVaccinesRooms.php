<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\Accession;
use Illuminate\Console\Command;
use App\Events\SubGroupCreatedEvent;
use App\Services\Configuration\GroupService;
use App\Services\Configuration\SubGroupService;

class CreateVaccinesRooms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:vaccinesrooms {countyid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create vaccines room';
    
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
        if ($this->argument('countyid') == 'all') {
            
            $accessionsWihtoutVacRooms = Accession::leftJoin('groups', 'accessions.county_id', '=', 'groups.county_id')
            ->select('accessions.*', 'groups.id')
            ->where('accessions.status', '=', 'aprovado_automaticamente')
            ->whereNull('groups.id')
            ->whereNull('accessions.deleted_at')
            ->orderBy('accessions.updated_at', 'asc')
            ->get();            

            foreach($accessionsWihtoutVacRooms as $accession) {                
                $countyId = $accession->county_id;
                $this->createVaccineRoom($countyId);                
            }

            return;
        }  
        
        $this->createVaccineRoom($this->argument('countyid')); 
        return;
    }

    private function createVaccineRoom(int $countyId)
    {
        if ($countyId == null || $countyId == '') return;

        $group = Group::where('county_id', $countyId)->count();
        if ($group > 0) {
            $this->info("Já existe regional criada para esse município - $countyId.");
            return;
        }

        $groupService = app()->make(GroupService::class);
        $params['name'] = 'Região';
        $params['county_id'] = $countyId;
        $group = $groupService->getRepository()->save($params);

        $subGroupService = app()->make(SubGroupService::class);
        $params['name'] = 'Sub Região';
        $params['group_id'] = $group->id;
        $subGroup = $subGroupService->getRepository()->save($params);

        event(new SubGroupCreatedEvent($subGroup));

        $this->info("Salas de vacina criada para o município $countyId.");
    }
}