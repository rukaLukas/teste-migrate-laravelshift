<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\State;
use App\Helper\Number;
use App\Models\County;
use App\Models\Region;
use App\Models\Profile;
use App\Models\Pronoun;
use App\Models\Accession;
use App\Models\Occupation;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Carbon;
use Tests\RefreshTestDatabase;
use App\Models\GovernmentAgency;
use App\Models\GovernmentOffice;
use Database\Seeders\UserSeeder;
use Database\Seeders\StateSeeder;
use App\Models\ReasonDelayVaccine;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Database\Factories\UserFactory;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\PronounSeeder;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Database\Factories\CountyFactory;
use Database\Seeders\AccessionSeeder;
use Database\Seeders\OccupationSeeder;
use Database\Seeders\TargetPublicSeeder;
use Database\Seeders\GovernmentAgencySeeder;
use Database\Seeders\GovernmentOfficeSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Database\Seeders\ReasonDelayVaccineSeeder;
use Database\Seeders\TypeReasonDelayVaccineSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GovernmentOfficeTest extends TestCase
{
    use RefreshTestDatabase;
    // use RefreshDatabase;
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
        $this->seed(UserSeeder::class);
        $this->seed(GovernmentOfficeSeeder::class);
        $this->url = '/api/government-offices';

        $this->userAuth = Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function should_list_default_government_offices()
    {
        $response = $this->get("{$this->url}/default");

        $response->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll('type', 'status', 'data', 'show')
                    ->has('data.data', null, fn ($json) =>
                        $json->has('id')
                            ->has('name')
                            ->has('email')
                            ->has('type')
                    )
            );
    }

    /** @test */
    public function should_create_government_office_by_county_without_bind_users()
    {
        $data = $this->createData(false);

        $response = $this->postJson("{$this->url}", $data);

        $response->assertStatus(201);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data.response')
                    ->where('data.response.name', $data['form']['name'])
                    ->where('data.response.email', $data['form']['email'])
                    ->where('data.response.county_id', $data['form']['county_id'])
                    ->etc()
            );
    }

    /** @test */
    public function should_create_government_office_by_county_with_bind_users()
    {
        $data = $this->createData();

        $response = $this->postJson("{$this->url}", $data);

        $response->assertStatus(201);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data.response')
                    ->where('data.response.name', $data['form']['name'])
                    ->where('data.response.email', $data['form']['email'])
                    ->where('data.response.county_id', $data['form']['county_id'])
                    ->etc()
            );

        $this->assertUsersInGovernmentOffice($data['userIn']->toArray(), $response->json()['data']['response']['uuid']);
    }

    /** @test */
    public function should_not_create_government_office_without_required_data()
    {
        $data['form'] = [
            'name' => '',
            'email' => '',
            'county_id' => '',
        ];

        $response = $this->postJson($this->url, $data);

        $response->assertJsonValidationErrors([
            'form.email',
            'form.name',
            'form.county_id'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function should_not_create_government_office_with_incorrect_data()
    {
        $data['form'] = [
            'name' => $this->faker->randomDigitNotNull(),
            'email' => $this->faker->name,
            'county_id' => $this->faker->numberBetween(900000, 950000),
        ];

        $response = $this->postJson($this->url, $data);

        $response->assertJsonValidationErrors([
            'form.email',
            'form.name',
            'form.county_id'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function should_update_government_office_without_bind_users()
    {
        $this->should_create_government_office_by_county_without_bind_users();

        $governmetnOffice = GovernmentOffice::all()->last();

        $data['form'] = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
        ];
        $data['userIn'] = [];

        $response = $this->putJson("{$this->url}/{$governmetnOffice->uuid}", $data);

        $response->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data.response')
                    ->where('data.response.name', $data['form']['name'])
                    ->where('data.response.email', $data['form']['email'])
                    ->where('data.response.county_id',  $governmetnOffice->county_id)
                    ->etc()
            );
    }

    /** @test */
    public function should_update_government_office_with_bind_users()
    {
        $this->should_create_government_office_by_county_without_bind_users();

        $governmetnOffice = GovernmentOffice::all()->last();
        $data['form'] = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
        ];
        $uuidsBindableUsers = $this->createBindlableUsers($governmetnOffice->county_id)->map(function ($user) {
            return $user->uuid;
        });
        $data['userIn'] = $uuidsBindableUsers;

        $response = $this->putJson("{$this->url}/{$governmetnOffice->uuid}", $data);

        $response->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data.response')
                    ->where('data.response.name', $data['form']['name'])
                    ->where('data.response.email', $data['form']['email'])
                    ->where('data.response.county_id',  $governmetnOffice->county_id)
                    ->etc()
            );
        $this->assertUsersInGovernmentOffice($data['userIn']->toArray(), $response->json()['data']['response']['uuid']);
    }

    /** @test */
    public function should_not_update_government_office_with_incorrect_data()
    {
        $this->should_create_government_office_by_county_without_bind_users();

        $governmetnOffice = GovernmentOffice::all()->last();
        $data['form'] = [
            'name' => $this->faker->randomDigitNotNull(),
            'email' => $this->faker->name,
        ];
        $data['userIn'] = [];

        $response = $this->putJson("{$this->url}/{$governmetnOffice->uuid}", $data);

        $response->assertJsonValidationErrors([
            'form.email',
            'form.name',
        ]);

        $response->assertStatus(422);
    }


    /**
     * createData function
     *
     * @param boolean $bindableUsers
     * @return void
     */
    private function createData($bindableUsers = true)
    {
        $data = [];

        $accession = $this->createAccession();
        $uuidsBindableUsers = $this->createBindlableUsers($accession->county_id)->map(function ($user) {
            return $user->uuid;
        });

        $data['form'] = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'county_id' => $accession->county_id,
        ];
        $data['userIn'] = $uuidsBindableUsers;

        return $data;
    }

    private function createAccession()
    {
         // TODO criar usuario com acession aprovado
         $occupation = Occupation::where('id', Occupation::GESTOR_POLITICO)->first();
         $userGestorPolitico = User::factory()->definition();
         $userGestorPolitico['occupation_id'] = $occupation->id;
         $userGestorPolitico = User::create($userGestorPolitico);

         $occupation = Occupation::where('id', Occupation::PREFEITO)->first();
         $userPrefeito = User::factory()->definition();
         $userPrefeito['county_id'] = $userGestorPolitico['county_id'];
         $userPrefeito['occupation_id'] = $occupation->id;
         $userPrefeito = User::create($userPrefeito);

         $accession = Accession::factory()->definition();
         $accession['prefeito_id'] = $userPrefeito['id'];
         $accession['gestor_politico_id'] = $userGestorPolitico['id'];
         $accession['county_id'] = $userGestorPolitico['county_id'];
         $accession['status'] = Accession::STATUS['APROVADO'];

         $accession = Accession::create($accession);

         return $accession;
    }

    private function assertUsersInGovernmentOffice(array $usersIn, string $governmentOfficeUuid)
    {
        foreach($usersIn as $userIn) {
            $this->assertDatabaseHas('government_office_users',
                [
                'user_id' => User::findByUUID($userIn)->id,
                'government_office_id' => GovernmentOffice::findByUUID($governmentOfficeUuid)->id
            ]);
        }
    }

    /**
     * createBindlableUsers function
     *
     * @param integer $countyId
     * @return Array
     */
    private function createBindlableUsers(int $countyId)
    {
        $users = [];
        for ($i = $this->faker->numberBetween(1, 5); $i > 0; $i--) {
            $occupation = $this->getBindableOccupations();
            $user = User::factory()->definition();
            $user['county_id'] = $countyId;
            $user['occupation_id'] = $occupation->id;
            $users[] = User::create($user);
        }

        return Collection::make($users);
    }

    private function getBindableOccupations()
    {
        return Occupation::inRandomOrder()
            ->get()
            ->filter(function($occupation) {
                return in_array($occupation->id, [
                    Occupation::COORDENADOR_OPERACIONAL_SAUDE,
                    Occupation::SUPERVISOR_INSTITUCIONAL_SAUDE,
                    Occupation::AGENTE_ALERTA,
                    Occupation::SUPERVISOR_INSTITUCIONAL_EDUCACAO,
                    Occupation::SUPERVISOR_INSTITUCIONAL_ASSISTENCIA_SOCIAL
                ]);
            })
            ->first();
    }
}
