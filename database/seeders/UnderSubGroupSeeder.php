<?php

namespace Database\Seeders;

use Ramsey\Uuid\Uuid;
use App\Models\SubGroup;
use App\Models\UnderSubGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnderSubGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = \App\Models\Group::all();
        $groupId = [];
        foreach ($groups as $group) {
            
            if ($group->county_id == null || $group->county_id == '') continue;            
            $codigoIbge = substr($group->county->codigo_ibge, 0, -1);
            $cnes = DB::table('cnes_estabelecimento')->where('CO_MUNICIPIO_GESTOR', $codigoIbge)->get();

            foreach($group->subGroups as $subGroup) {
                if(array_search($group->county_id, $groupId) === false) {                    
                    $groupId[] = $group->county_id;
                    foreach ($cnes as $cne) {                        
                        UnderSubGroup::updateOrCreate(
                            [
                                'name' => $cne->NO_FANTASIA,
                                'sub_group_id' => $subGroup->id,                                      
                            ],
                            [
                                'logradouro' => $cne->NO_LOGRADOURO,
                                'endereco' => $cne->NU_ENDERECO,
                                'bairro' => $cne->NO_BAIRRO,
                                'latitude' => $cne->NU_LATITUDE,
                                'longitude' => $cne->NU_LONGITUDE, 
                            ]
                        );                                    
                    }
                }                                       
            }
        } 
    }
}
