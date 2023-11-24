<?php

namespace Tests\Feature;

use App\Models\Accession;
use App\Models\County;
use App\Models\Occupation;
use App\Models\Pronoun;
use App\Models\State;
use App\Models\User;
use Database\Seeders\CnesEstabelecimentoSeeder;
use Database\Seeders\CountySeeder;
use Database\Seeders\GovernmentOfficeSeeder;
use Database\Seeders\OccupationSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\PronounSeeder;
use Database\Seeders\RegionSeeder;
use Database\Seeders\StateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AccessionTest extends TestCase
{
    // use RefreshTestDatabase;
    use RefreshDatabase;
    use WithFaker;

    protected $userAuth;

    public function setup(): void
    {
        parent::setUp();

        $this->seed(RegionSeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CountySeeder::class);
        $this->seed(OccupationSeeder::class);
        $this->seed(PronounSeeder::class);
        $this->seed(ProfileSeeder::class);
        $this->seed(GovernmentOfficeSeeder::class);
        $this->url = '/api/accession';
        $this->withoutExceptionHandling();

        $userAuth = Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );

        $this->actingAs($userAuth);
    }

    /**
     * @test
     * @group create_accession
     */
    public function should_create_accession()
    {
        $data = $this->createRandomAccessionData();

        $response = $this->postJson("{$this->url}/register", $data);
        $response->assertStatus(200);

        $this->assertDatabaseCount('accessions', 1);
        $this->assertDatabaseCount('users', 3);
    }

    /** @test */
    public function should_delete_accession()
    {
        Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );

        $data = $this->createRandomAccessionData();

        $response = $this->postJson("{$this->url}/register", $data);

        $accession = Accession::latest()->first();

        $response = $this->delete("{$this->url}/$accession->id");

        $response->assertStatus(200);

        $accessionDeleted = Accession::findByUUID($accession->uuid);

        $this->assertDatabaseCount('accessions', 1);
        $this->assertEquals(null, $accessionDeleted);
    }

    /**
     * @test
     */
    public function should_create_default_government_agencies_when_accession_approved()
    {
        $this->markTestSkipped();
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $data = $this->createRandomAccessionData();

        $response = $this->postJson("{$this->url}/register", $data);

        $this->assertDatabaseCount('accessions', 1);

        $id = Accession::latest()->first()->id;

        $response = $this->putJson("{$this->url}/{$id}/confirm");

        $response->assertStatus(201);

        $user = User::orderBy('id', 'desc')->first();

        $this->assertDatabaseCount('government_offices', 4);

        $this->assertDatabaseHas('government_offices', [
            'county_id' => $user['county_id'],
        ]);

//        $users = array_slice(User::all()->toArray(), 1, 3);
//        foreach ($users as $user) {
//            $this->assertDatabaseHas('government_offices', [
//                'county_id' => $user['county_id'],
//            ]);
//        }
    }

    /**
     * @test
     */
    public function should_create_default_regional_subregional_and_vaccines_room_when_accession_approved()
    {
        $this->markTestSkipped('skipped');
        $this->seed(CnesEstabelecimentoSeeder::class);

        Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );
        $data = $this->createRandomAccessionData();

        $response = $this->postJson("{$this->url}/register", $data);

        $this->assertDatabaseCount('accessions', 1);

        $id = Accession::latest()->first()->id;

        $response = $this->putJson("{$this->url}/{$id}/confirm");

        $response->assertStatus(201);

        // check if exists records to regional(groups) by county
        $this->assertDatabaseHas('groups', [
            'county_id' => $data['form1']['county_id'],
        ]);

        // check if exists records to subregional(sub_groups) by group_id
        $group = DB::table('groups as g')
                 ->join('sub_groups as sg', 'sg.group_id', '=', 'g.id')
                ->selectRaw("g.id as group_id, sg.id as sub_group_id")
                ->where('county_id', $data['form1']['county_id'])
                ->first();
        $this->assertDatabaseHas('sub_groups', [
            'group_id' => $group->group_id,
        ]);

        // check if exists records to vaccines rooms by regional(sub_groups)
//        $this->assertDatabaseHas('under_sub_groups', [
//            'sub_group_id' => $group->sub_group_id,
//        ]);
    }

    /**
     * @test
     */
    public function should_return_status_422_when_create_accession_with_incorrect_data()
    {
        $data = $this->createWrongAccessionData();

        $response = $this->postJson("{$this->url}/register", $data);

        $response->assertJsonValidationErrors([
            'form1.state_id',
            'form1.county_id',
            'form2.name',
            'form2.cpf',
            'form2.birthdate',
            'form2.email',
            'form2.name',
            'form2.office_phone',
            'form2.cell_phone',
            'form2.occupation',
            'form3.name',
            'form3.cpf',
            'form3.birthdate',
            'form3.email',
            'form3.name',
            'form3.office_phone',
            'form3.cell_phone',
            'form3.occupation',
        ]);
    }

    /**
     * @test
     */
    public function should_return_status_422_when_create_accession_with_duplicate_email_prefeito_and_gestor_politico()
    {
        $data = $this->createRandomAccessionData();
        $data['form2']['email'] = $data['form3']['email'];

        $response = $this->postJson("{$this->url}/register", $data);

        $response->assertJson(array('errors' => [
            'form3.email' => [
                'Os campos E-mail Gestor Político e E-mail Prefeito devem ser diferentes.'
            ]
        ]));
    }


    /**
     * @test
     */
    public function should_return_status_422_when_create_accession_with_email_that_already_exists()
    {
        $data = $this->createRandomAccessionData();

        $this->postJson("{$this->url}/register", $data);

        $user = User::orderBy('id', 'desc')->first();
        $data = $this->createRandomAccessionData();
        $data['form2']['email'] = $user->email;


        $response = $this->postJson("{$this->url}/register", $data);

        $response->assertJson(array('errors' => [
            'form2.email' => [
                'O campo E-mail Prefeito já está sendo utilizado.'
            ]
        ]));
    }

    /** @test */
    public function should_return_status_422_when_create_accession_with_cpf_that_already_exists()
    {
        $data = $this->createRandomAccessionData();

        $this->postJson("{$this->url}/register", $data);

        $user = User::orderBy('id', 'desc')->first();
        $data = $this->createRandomAccessionData();
        $data['form2']['cpf'] = $user->cpf;


        $response = $this->postJson("{$this->url}/register", $data);

        $response->assertJson(array('errors' => [
            'form2.cpf' => [
                'O campo CPF Prefeito já está sendo utilizado.'
            ]
        ]));
    }

     /** @test */
     public function should_return_status_422_when_create_accession_to_county_that_already_exists_in_bav()
     {
        // TODO testar se consigo criar uma adesao para um municipio que ja existe na BAV sem as validacoes que foram adicionadas ou sej acom a versao qu esrta em producao

         $data = $this->createRandomAccessionData();

         $this->postJson("{$this->url}/register", $data);

         $user = User::orderBy('id', 'desc')->first();
         $data = $this->createRandomAccessionData();
         $data['form1']['county_id'] = $user->county_id;


         $response = $this->postJson("{$this->url}/register", $data);

         $response->assertJson(array('errors' => [
             'form1.county_id' => [
                 'Já existe registro para esse município na BAV.'
             ]
         ]));
     }

    private function createRandomAccessionData()
    {
        $data = [];
        $data['form1'] = [
            'state_id' => State::find(1)->uuid,
            'county_id' => County::where('state_id', 1)->first()->id,
        ];
        $data['form2'] = [
            'pronoun' => Pronoun::inRandomOrder()->first()->uuid,
            'name' => str_replace('.', '', $this->faker()->name),
            'cpf' => $this->ptBrFaker->cpf,
            'birthdate' => $this->faker()->unique()->dateTimeBetween($startDate = "-30 years", $endDate = "-19 years")->format('Y/m/d'),
            'email' => $this->faker()->email,
            'group' => '',
            'subGroup' => '',
            'underSubGroup' => '',
            'occupation' => Occupation::where('name', 'Prefeito')->first()->uuid,
            'office_phone' => sprintf('(%s) %s', $this->ptBrFaker->areaCode, $this->ptBrFaker->landline),
            'cell_phone' => sprintf('(%s) %s', $this->ptBrFaker->areaCode, $this->ptBrFaker->landline) . $this->faker()->randomNumber(1)
        ];
        $data['form3'] = [
            'pronoun' => Pronoun::inRandomOrder()->first()->uuid,
            'name' => str_replace('.' , '', $this->faker()->name),
            'cpf' => $this->ptBrFaker->cpf,
            'birthdate' => $this->faker()->unique()->dateTimeBetween($startDate = "-30 years", $endDate = "-19 years")->format('Y/m/d'), //$this->faker()->date('d/m/Y'),
            'email' => $this->faker()->email,
            'group' => '',
            'subGroup' => '',
            'underSubGroup' => '',
            'occupation' => Occupation::where('name', 'Gestor(a) político(a)')->first()->uuid,
            'office_phone' => sprintf('(%s) %s', $this->ptBrFaker->areaCode, $this->ptBrFaker->landline),
            'cell_phone' => sprintf('(%s) %s', $this->ptBrFaker->areaCode, $this->ptBrFaker->landline) . $this->faker()->randomNumber(1)
        ];
        return $data;
    }

    private function createWrongAccessionData(): array
    {
        $data = $this->createRandomAccessionData();
        $data['form1']['state_id'] = "9999";
        $data['form1']['county_id'] = 9999;
        $forms = ['form2', 'form3'];
        foreach ($forms as $form) {

            $data[$form]['name'] = $this->faker()->asciify('***');
            $data[$form]['cpf'] = substr_replace($data[$form]['cpf'], Str::random(2), -2);
            $data[$form]['birthdate'] = $this->faker()->dateTimeBetween('-16 years', '-14 years')->format('Y/m/d');
            $data[$form]['email'] = $this->faker()->name;
            $occupations = [
                'Agente de alerta',
                'Técnico verificador',
                'Supervisor(a) institucional da educação',
                'Supervisor(a) institucional da assistência social',
                'Supervisor(a) institucional da saúde',
                'Coordenador(a) operacional da saúde',
                'Gestor nacional'
            ];
            $nameOccupation = $occupations[array_rand($occupations)];
            $data[$form]['occupation'] = Occupation::where('name', $nameOccupation)->first()->uuid;
            $data[$form]['office_phone'] = $this->faker()->asciify('******');
            $data[$form]['cell_phone'] = $this->faker()->asciify('*******');
        }

        array_merge($data['form1'], $data['form2'], $data['form3']);

        return $data;
    }
}
