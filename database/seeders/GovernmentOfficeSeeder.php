<?php

namespace Database\Seeders;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Occupation;
use Illuminate\Database\Seeder;
use App\Models\GovernmentOffice;

class GovernmentOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        // cria government offices default
        foreach(GovernmentOffice::DEFAULT as $default) {
            $governmentOffice = new GovernmentOffice();
            $governmentOffice->name = $default['name'];
            $governmentOffice->email = $default['email'];
            $governmentOffice->county_id = null;
            $governmentOffice->type = $default['type'];
            $governmentOffice->uuid = Uuid::uuid4();
            $governmentOffice->save();                
        }
      
        $users = User::where('name', '!=', 'admin')
            ->where('occupation_id', Occupation::GESTOR_POLITICO)
            ->get();                
        foreach ($users as $key => $user) {                
            foreach(GovernmentOffice::DEFAULT as $key => $value) {              
                if ($user->accession != null && ($user->accession->status == 'aprovado' || $user->accession->status == 'aprovado_automaticamente')) {                                                            
                    $value['county_id'] = $user->county_id;
                    GovernmentOffice::create($value);                    
                }                              
            }
        }
        
    }
}
