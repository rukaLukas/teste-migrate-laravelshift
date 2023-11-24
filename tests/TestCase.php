<?php

namespace Tests;

use App\Models\User;
use App\Models\Group;
use App\Models\SubGroup;
use App\Models\Accession;
use App\Models\Occupation;
use App\Models\UnderSubGroup;
use Illuminate\Support\Facades\URL;
use Database\Seeders\GovernmentOfficeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $ptBrFaker;
    protected $url;

    public function setup(): void
    {
        parent::setUp();        

        $this->ptBrFaker = \Faker\Factory::create('pt_BR');
        URL::forceRootUrl('http://' . env('TEST_DOMAIN'));      
    }

    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[RefreshTestDatabase::class])) {
            $this->refreshTestDatabase();
        }

        return $uses;
    }

    protected function findValidationMessages(string $message, array $errorsMessage): bool
    {
        foreach ($errorsMessage as $key => $val) {
            $error = array_values($val);
            if (array_search($message, $error) !== false) {
                return true;
            } 
        }

        return false;
    }

    protected function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    public function accessionJourney(): array
    {
        // TODO create a user
        $accession = Accession::first();

        $userPrefeito = User::factory()->create();
        $userGestorPolitico = User::factory()->create();
        $userPrefeito['occupation_id'] = Occupation::PREFEITO;
        $userPrefeito['county_id'] = $accession->county_id;
        $userPrefeito->save();
        $userGestorPolitico['occupation_id'] = Occupation::GESTOR_POLITICO;
        $userGestorPolitico['county_id'] = $accession->county_id;
        $userGestorPolitico->save();
        // dd($userPrefeito->county_id, County::where('id', $userPrefeito->county_id)->first());

        $accession = Accession::first();
        $accession->prefeito_id = $userPrefeito->id;
        $accession->gestor_politico_id = $userGestorPolitico->id;
        $accession->status = Accession::STATUS['APROVADO_AUTOMATICAMENTE'];
        $accession->status_prefeito = Accession::STATUS['CONFIRMADO'];
        $accession->status_gestor_politico = Accession::STATUS['CONFIRMADO'];
        $accession->save();

        $this->seed(GovernmentOfficeSeeder::class);

        // simulate the UnderSubGroupSeeder
        $groups = Group::factory()->count(2)->create([
            'name' => $this->ptBrFaker->name,
            'county_id' => $accession->county_id,            
        ]);
        $subGroups = SubGroup::factory()->count(2)->create([
            'name' => $this->ptBrFaker->name,
            'group_id' => $groups[0]->id,
        ]);
        $underSubGroups = UnderSubGroup::factory()->count(7)->create([
            'name' => $this->ptBrFaker->name,
            'sub_group_id' => $subGroups[rand(0, count($subGroups)-1)]->id,
        ]);
                
        return [
            'userPrefeito' => $userPrefeito,
            'userGestorPolitico' => $userGestorPolitico,
        ];
    }
}

