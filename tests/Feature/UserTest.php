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
use Database\Seeders\UserSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Database\Seeders\PronounSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Database\Factories\CountyFactory;
use Database\Seeders\AccessionSeeder;
use Database\Seeders\OccupationSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    // use RefreshTestDatabase;
    // use DatabaseMigrations;
    use RefreshDatabase;
    use WithFaker;

    protected $profile;
    private $userCreateData;
    private $userUpdateData;
    protected $userAuth;

    public function setup(): void
    {
        parent::setUp();

        $this->profile = Profile::factory()->create();
        $this->seed(PronounSeeder::class);
        $this->seed(OccupationSeeder::class);
        $this->seed(RegionSeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CountySeeder::class);
        $this->url = '/api/users';
        $this->userAuth = Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );
    }

    /** @test */
    public function should_list_users()
    {
        $randomNumber = $this->faker->numberBetween(1, 5);
        $users = $this->createRandomUsers($randomNumber);

        $response = $this->get($this->url);

        $response->assertJsonCount($randomNumber + 1, 'data.data');

        $response->assertStatus(200);
    }

    /** @test */
    public function should_show_a_single_user()
    {
        $response = $this->get($this->url . '/' . $this->userAuth->id);

        $response->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 27)
                    ->where('data.name', $this->userAuth->name)
                    ->etc()
            );
    }

    /** @test */
    public function should_return_status_422_when_consult_nonexisted_user()
    {
        $id = 10;

        $response = $this->get($this->url . '/' . $id);

        $response->assertStatus(422);
    }

    /** @test */
    public function should_not_register_user_with_duplicate_email()
    {
        $this->data['email'] = Auth::user()->email;

        $response = $this->postJson($this->url, $this->data);

        $response->assertStatus(422);
    }

    /** @test */
    public function should_not_register_user_without_required_data()
    {
        $data = [
            'email' => '',
        ];
        $response = $this->postJson($this->url, $data);

        $response->assertJsonValidationErrors([
            'email',
            'name',
            'birthdate',
            'cpf',
            'cell_phone',
            'office_phone'
        ]);

        $response->assertStatus(422);
    }

    // TODO should_not_register_user_with_invalid_data
    /** @test */
    public function should_not_register_user_with_invalid_data()
    {
        $maxAge = Carbon::now()->subYear($this->faker->numberBetween(1, 17))->format('Y-m-d');
        $data = [
            'pronoun' => $this->faker->uuid,
            'name' => '',
            'cpf' => $this->faker->numberBetween(1, 1000),
            'birthdate' => $maxAge,
            'email' => $this->faker->name(),
            'group' => '',
            'subGroup' => '',
            'underSubGroup' => '',
            'occupation' => $this->faker->randomLetter(),
            'office_phone' => $this->faker->randomNumber(7, true),
            'cell_phone' => $this->faker->randomNumber(7, true),
            'county_id' => $this->faker->sentence(5),
        ];

        $response = $this->postJson($this->url, $data);

        $response->assertJsonValidationErrors([
            'pronoun',
            'email',
            'name',
            'birthdate',
            'cpf',
            'cell_phone',
            'office_phone',
            'county_id'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function should_create_user()
    {
        $userData = $this->createUserData();
        
        $response = $this->postJson($this->url, $userData);
        
        $user = User::orderBy('id', 'desc')->first();

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(19, User::count());
        $this->assertEquals($userData['email'], $user->email);
    }

    /** @test */
    public function should_not_create_user_to_county_wihtout_approved_accession()
    {
        $userData = $this->createUserData();
        $accession = Accession::inRandomOrder()->first();
        $accession->status = 'pendente';
        $accession->save();

        $response = $this->postJson($this->url, $userData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'accession'
        ]);
    }

    /** @test */
    public function should_not_create_user_user_with_cpf_an_existing_cpf()
    {
        $userData = $this->createUserData();
        $accession = Accession::inRandomOrder()->first();
        $accession->status = 'pendente';
        $accession->save();
        $user = User::inRandomOrder()->first();
        $userData['cpf'] = $user->cpf;

        $response = $this->postJson($this->url, $userData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'accession',
            'cpf',
        ]);
    }

    /** @test */
    public function should_not_update_user_with_duplicate_email()
    {
        $userData = $this->createUserData();
        $user = User::factory(2)->create();
        $userData['email'] = $user[1]->email;

        $response = $this->put("{$this->url}/{$user[0]->id}", $userData);

        $response->assertStatus(422);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('errors', 1)
                    ->etc()
            );
    }

    /** @test */
    public function should_not_update_user_with_duplicate_cpf()
    {
        $userData = $this->createUserData();
        $user = User::factory(2)->create();
        $userData['cpf'] = $user[1]->cpf;

        $response = $this->put("{$this->url}/{$user[0]->id}", $userData);

        $response->assertStatus(422);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('errors', 1)
                    ->etc()
            );
    }

    /** @test */
    public function should_return_status_422_when_update_user_with_incorrect_data()
    {
        $data = [
            'pronoun' => 10,
            'name' => $this->faker->randomLetter(),
            'cpf' => $this->ptBrFaker->cellphoneNumber(),
            'birthdate' => 'uncomun',
            'email' => $this->faker->name,
            'group' => '',
            'subGroup' => '',
            'underSubGroup' => '',
            'occupation' => 10,
            'office_phone' => $this->faker->randomNumber(7, true),
            'cell_phone' => $this->faker->randomNumber(7, true),
            'county_id' => $this->faker->name(),
        ];

        $response = $this->put("{$this->url}/{$this->userAuth->id}", $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'pronoun',
            'email',
            'name',
            'email',
            'birthdate',
            'cpf',
            'cell_phone',
            'office_phone',
            'occupation',
            'county_id'
        ]);
    }

    /**
     * @test
     * @group should-update-user
     */
    public function should_update_user()
    {
        $userData = $this->createUserData();
        $id = User::inRandomOrder()->first()->id;
        $user = User::inRandomOrder()->first();
        $userData['cpf'] = $user->cpf;
        $userData['id'] = $user->uuid;

        $response = $this->put("{$this->url}/{$id}", $userData);

        $response->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data.response')
                    ->where('data.response.id', $id)
                    ->where('data.response.name', $userData['name'])
                    ->where('data.response.email', $userData['email'])
                    ->where('data.response.cpf', Number::onlyNumbers($userData['cpf']))
                    ->where('data.response.birthdate', Carbon::createFromFormat('Y-m-d', $userData['birthdate'])->format('Y-m-d'))
                    ->where('data.response.cell_phone', Number::onlyNumbers($userData['cell_phone']))
                    ->where('data.response.office_phone', Number::onlyNumbers($userData['office_phone']))
                    ->where('data.response.county_id', $userData['county_id'])
                    ->etc()
            );
    }

    /** @test */
    public function should_delete_user()
    {
        do {
            $user = $this->createRandomUsers(1)->first();
        } while ($user->occupation_id == 1 || $user->occupation_id == 2 || $user->occupation_id == 7);

        $response = $this->deleteJson("{$this->url}/{$user->uuid}");

        $response->assertStatus(200);
    }

    /** @test */
    public function should_not_delete_user_types_adminmaster_or_prefeito_or_gestor_politico()
    {
        $response = $this->deleteJson("{$this->url}/{$this->userAuth->uuid}");

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'id' => 'Não permitido exclusão de usuários do tipo super administrador, prefeito e gestor político.'
        ]);

        $this->assertEquals(1, User::count());
    }

    private function createUserData()
    {
        $this->seed(UserSeeder::class);
        $this->seed(AccessionSeeder::class);

        $accession = Accession::inRandomOrder()->first();
        $accession->status = 'aprovado_automaticamente';
        $accession->status_prefeito = 'confirmado';
        $accession->status_gestor_politico = 'confirmado';
        $accession->save();

        $minAge = Carbon::now()->subYears(18)->format('Y-m-d');
        $userData = [
            'pronoun' => Pronoun::inRandomOrder()->first()->uuid,
            'name' => str_replace('.', '', $this->faker->name() . ' ' . $this->faker->lastName()),
            'cpf' => $this->ptBrFaker->cpf,
            'birthdate' => $this->faker->date('Y-m-d', $minAge),
            'email' => $this->faker->unique()->safeEmail(),
            'group' => '',
            'subGroup' => '',
            'underSubGroup' => '',
            'occupation' => Occupation::where('name', '=', 'Agente de alerta')->first()->uuid,
            'office_phone' => sprintf('(%s) %s', $this->ptBrFaker->areaCode, $this->ptBrFaker->landline),
            'cell_phone' => sprintf('(%s) %s', $this->ptBrFaker->areaCode, $this->ptBrFaker->landline),
            'county_id' => Accession::inRandomOrder()->first()->county_id,
        ];

        return $userData;
    }

    private function createRandomUsers(int $number)
    {
        $firstUser = USER::first()->get()[0];
        $users = User::factory()->count($number)->create([
            'county_id' => $firstUser->county_id,
            // 'occupation_id' => $firstUser->occupation_id,
            'pronoun_id' => $firstUser->pronoun_id,
        ]);

        return $users;
    }
}
