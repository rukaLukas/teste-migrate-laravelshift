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
use Database\Seeders\OccupationSeeder;
use Database\Seeders\TargetPublicSeeder;
use Database\Seeders\GovernmentAgencySeeder;
use Database\Seeders\GovernmentOfficeSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Database\Seeders\ReasonDelayVaccineSeeder;
use Database\Seeders\TypeReasonDelayVaccineSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\ReasonDelayVaccineTargetPublicSeeder;
use Database\Seeders\ReasonDelayVaccineGovernmentOfficeSeeder;

class ReasonDelayVaccineTest extends TestCase
{
    use RefreshTestDatabase;
    // use RefreshDatabase;
    use WithFaker;

    protected $userAuth;

    public function setup(): void
    {
        parent::setUp();

        $this->seed(PronounSeeder::class);
        $this->seed(OccupationSeeder::class);
        $this->seed(RegionSeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CountySeeder::class);
        $this->seed(ProfileSeeder::class);
        $this->seed(TargetPublicSeeder::class);
        $this->seed(TypeReasonDelayVaccineSeeder::class);
        $this->seed(GovernmentOfficeSeeder::class);
        $this->seed(ReasonDelayVaccineSeeder::class);
        $this->seed(ReasonDelayVaccineGovernmentOfficeSeeder::class);
        $this->seed(ReasonDelayVaccineTargetPublicSeeder::class);
        $this->url = '/api/reasons-vaccine-delay';

        $this->userAuth = Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function should_list_reasons_delay_vaccine()
    {
        $response = $this->get($this->url);

        $response->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll('type', 'status', 'data', 'show')
                    ->has('data.data', null, fn ($json) =>
                        $json->has('id')
                            ->has('description')
                            ->has('target_public', null, fn ($json) =>
                                $json->has('id')
                                    ->has('description')
                            )
                            ->has('type_reason_delay_vaccine.id')
                            ->has('type_reason_delay_vaccine.description')
                            ->has('forwarding')
                    )
            );
    }

    /** @test */
    public function should_bind_reason_delay_vaccine_to_government_offices()
    {
        $data = $this->createData();

        $user = User::orderBy('id', 'desc')->first();

        $response = $this->putJson("{$this->url}/county/{$user->county_id}", $data);

        $response->assertStatus(201);

        GovernmentOffice::where('county_id', $user->county_id)->get()->each(function ($governmentOffice) use ($data) {
            $this->assertDatabaseHas('go_rdv', [
                'government_office_id' => $governmentOffice->id,
            ]);
        });
    }

    private function createData()
    {
        $data = [];
        $data['reason_delay_vaccine_id'] = ReasonDelayVaccine::inRandomOrder()->first()->uuid;

        // criar um usuario aleatorio
        $user = UserFactory::new()->create();

        // vincular esse usuario a government offices
        foreach(GovernmentOffice::DEFAULT as $default) {
            $governmentOffice = new GovernmentOffice();
            $governmentOffice->name = $default['name'];
            $governmentOffice->email = $default['email'];
            $governmentOffice->county_id = $user->county_id;
            $governmentOffice->type = $default['type'];
            $governmentOffice->uuid = $this->faker->uuid;
            $governmentOffice->save();
        }

        $governmentOffices = GovernmentOffice::Where('county_id', $user->county_id)->get();
        $data['government_offices'] = $governmentOffices->pluck('uuid')->toArray();

        return $data;
    }
}
