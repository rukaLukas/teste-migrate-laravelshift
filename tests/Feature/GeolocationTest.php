<?php

namespace Tests\Feature;

use App\Models\Occupation;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Tests\RefreshTestDatabase;
use App\Models\GovernmentAgency;
use Database\Seeders\StateSeeder;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Database\Seeders\PronounSeeder;
use Database\Seeders\OccupationSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GeolocationTest extends TestCase
{
    use RefreshTestDatabase;
    use WithFaker;

    protected $postalCode;
    protected $lat;
    protected $lng;
    protected static $initialized = false;

    public function setup(): void
    {
        parent::setUp();

        Profile::factory()->count(3)->create();
        $this->seed(PronounSeeder::class);
        $this->seed(OccupationSeeder::class);
        $this->seed(RegionSeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CountySeeder::class);
        Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );

        $this->url = '/api/geolocation';
        $this->postalCode = "41620-170";
        $this->lat = -12.95214;
        $this->lng = -38.35712;
    }

    /** @test */
    public function should_show_address_by_postalcode()
    {
        $response = $this->get("{$this->url}/postalcode/{$this->postalCode}");

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 8)
                    ->where('data.stateCode', 'BA')
                    ->where('data.state', 'Bahia')
                    ->where('data.city', 'Salvador')
                    ->where('data.postalCode', '41620-170')
                    ->where('data.lat', -12.95223)
                    ->where('data.lng', -38.35705)
                    ->etc()
            );
    }

    /** @test */
    public function should_return_status_422_when_provide_non_existed_postalcode()
    {
        $response = $this->get("{$this->url}/postalcode/234560");

        $response->assertStatus(422);

        $response->assertJson(array('errors' => [
            'Endereco não encontrado, ou cep inválido'
        ]));
    }

    /** @test */
    public function should_show_address_by_reverse_geocode()
    {
        $response = $this->get("{$this->url}/reverse-geocode/{$this->lat},{$this->lng}");

        $response->assertStatus(200);

        $response->assertJson([
            'status' => 200,
            'data' => [
                'stateCode' => 'BA',
                'state' => 'Bahia',
                'city' => 'Salvador',
                'district' => 'Itapuã',
		        'street' => 'Rua Seara Vermelha',
                'postalCode' => '41620-170',
                'lat' => -12.95217,
                'lng' => -38.35714
            ],
        ]);
    }

    /** @test */
    public function should_return_status_422_when_provide_wrong_data_type_latitude_longitude()
    {
        $response = $this->get("{$this->url}/reverse-geocode/-12.95214,--38.35712");

        $response->assertStatus(422);

        $response->assertJson(array('errors' => [
            'latitude e longitude devem ser do tipo float'
        ]));
    }
}
