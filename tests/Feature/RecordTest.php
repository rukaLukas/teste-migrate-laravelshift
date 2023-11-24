<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Alert;
use App\Models\Group;
use App\Helper\Number;
use App\Models\Profile;
use App\Models\Vaccine;
use App\Models\SubGroup;
use App\Models\Accession;
use App\Models\Occupation;
use App\Models\TargetPublic;
use Laravel\Sanctum\Sanctum;
use App\Models\UnderSubGroup;
use Illuminate\Support\Carbon;
use App\Models\GovernmentAgency;
use Database\Seeders\UserSeeder;
use App\Models\UnderSubGroupUser;
use Database\Seeders\BreedSeeder;
use Database\Seeders\GenreSeeder;
use Database\Seeders\StateSeeder;
use Illuminate\Http\UploadedFile;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Database\Seeders\PronounSeeder;
use Database\Seeders\VaccineSeeder;
use Database\Seeders\DeadlineSeeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeStatusVaccination;
use Database\Seeders\AccessionSeeder;
use Database\Seeders\OccupationSeeder;
use Database\Seeders\StatusAlertSeeder;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\TargetPublicSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\ReasonDelayVaccineSeeder;
use Database\Seeders\CnesEstabelecimentoSeeder;
use Database\Seeders\TypeStatusVaccinationSeeder;
use Database\Seeders\TypeReasonDelayVaccineSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $alert;

    public function setup(): void
    {
        parent::setUp(); 

        $this->profile = Profile::factory()->count(3)->create();
        $this->seed(TargetPublicSeeder::class);
        $this->seed(TypeStatusVaccinationSeeder::class);
        $this->seed(GenreSeeder::class);
        $this->seed(BreedSeeder::class);
        $this->seed(VaccineSeeder::class);
        $this->seed(TypeReasonDelayVaccineSeeder::class);
        $this->seed(ReasonDelayVaccineSeeder::class);
        $this->seed(RegionSeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CountySeeder::class);                
        $this->seed(PronounSeeder::class);
        $this->seed(OccupationSeeder::class);
        $this->seed(StatusAlertSeeder::class);
        $this->seed(UserSeeder::class);
        GovernmentAgency::factory()->count(3)->create();
        $this->seed(AccessionSeeder::class);
        $this->alert = Alert::factory()->create();      
    }

    /** @test */
    public function should_list_records_where_linked_to_vaccine_rooms_where_user_is_linked_to()
    {
        // set user in under_sub_group_users                 
        $alertData = $this->generateDataVisit(); 
        $alertData['county_id'] = Auth::user()->county_id;
        $this->postJson('/api/alerts', $alertData);

        // linked user to under_sub_group
        $alertData = $this->generateDataVisit();
        UnderSubGroupUser::factory()->create([
            'user_id' => Auth::user()->id,
            'under_sub_group_id' => UnderSubGroup::inRandomOrder()->first()->id,
        ]);
        // create record with vaccine room that auth user is linked to
        $alertData['vaccine_room_id'] = UnderSubGroup::where('id', '=', UnderSubGroupUser::where('user_id', '=', Auth::user()->id)
            ->first()->under_sub_group_id)
            ->first()->uuid;
        $alertData['county_id'] = Auth::user()->county_id;       
        $this->postJson('/api/alerts', $alertData);
                
        $this->getJson('/api/records')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data.data');        
    }

    private function baseData(): array
    {
        $accession = $this->accessionJourney();
        $this->seed(StatusAlertSeeder::class);
        Sanctum::actingAs(
            $accession['userGestorPolitico'],
            ['*']
        );
        $deadLineSeeder = app()->make(DeadlineSeeder::class);
        $deadLineSeeder->run(Auth::user()->county_id);


        Storage::fake('public');
        $file = UploadedFile::fake()->image('vacinacao.jpg');
        $responseUpload = $this->post('/api/upload-image', [
            'image' => $file,
        ]);

        $alertData = Alert::factory()->definition();
        $alertData['cpf'] = Number::onlyNumbers($this->ptBrFaker->cpf);
        $alertData['suscard'] = $this->faker->numerify('###############');
        $alertData['type_status_vaccination_id'] = TypeStatusVaccination::where('name', 'Vacinação em dia')->first()->uuid;
        $alertData['target_public_id'] = TargetPublic::find($alertData['target_public_id'])->uuid;
        $alertData['visit_date'] = $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d');
        $alertData['vaccine_card_pictures'] = array([
            'image' => $responseUpload->json()['url'],
        ]);
        $vaccine = $this->faker->randomElement(Vaccine::all()->toArray());
        $alertData['vaccine_scheduled_alerts'] = [
            [
                'vaccine_id' => $vaccine['uuid'],
                'vaccination_step' => $vaccine['uuid'],
                'previous_application' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
                'next_application' => Carbon::now()->addDay($vaccine['days_interval'])->toDateString(),
            ],
        ];
        $alertData['county_id'] = Auth::user()->county_id;
        $alertData['reasons_delay_vaccine'] = [];

        unset($alertData['reason_not_has_vac_card_pic']);
        unset($alertData['is_alert']);
        unset($alertData['is_visit']);
        unset($alertData['record_id']);

        return $alertData;
    }

    private function generateDataVisit(): array
    {
        return $this->baseData();
    }
}
