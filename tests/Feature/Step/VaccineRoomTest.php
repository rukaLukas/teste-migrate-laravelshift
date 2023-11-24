<?php
namespace Tests\Feature\Step;

use Tests\TestCase;
use App\Models\User;
use App\Models\Alert;
use App\Helper\Number;
use App\Models\Record;
use App\Models\Profile;
use App\Models\Vaccine;
use App\Models\Deadline;
use App\Models\Accession;
use App\Models\AlertStep;
use App\Models\Occupation;
use App\Models\StatusAlert;
use App\Models\TargetPublic;
use Laravel\Sanctum\Sanctum;
use App\Models\DelayedVaccine;
use Illuminate\Support\Carbon;
use Tests\RefreshTestDatabase;
use Database\Seeders\UserSeeder;
use Database\Seeders\AlertSeeder;
use Database\Seeders\BreedSeeder;
use Database\Seeders\GenreSeeder;
use Database\Seeders\StateSeeder;
use Illuminate\Http\UploadedFile;
use App\Models\ReasonDelayVaccine;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\PronounSeeder;
use Database\Seeders\VaccineSeeder;
use Database\Seeders\DeadlineSeeder;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeStatusVaccination;
use App\Models\VaccineScheduledAlert;
use Database\Seeders\AccessionSeeder;
use Database\Seeders\OccupationSeeder;
use Database\Seeders\StatusAlertSeeder;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\TargetPublicSeeder;
use Database\Seeders\GovernmentOfficeSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Database\Seeders\ReasonDelayVaccineSeeder;
use Database\Seeders\TypeStatusVaccinationSeeder;
use Database\Seeders\TypeReasonDelayVaccineSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Resources\Configuration\GovernmentOfficeResource;

class VaccineRoomTest extends TestCase
{
    // use RefreshTestDatabase;
    use RefreshDatabase;
    // use DatabaseMigrations;
    use WithFaker;

    protected $userAuth;

    public function setup(): void
    {
        parent::setUp();
        $this->profile = Profile::factory()->count(3)->create();
        $this->seed(TargetPublicSeeder::class);
        $this->seed(TypeStatusVaccinationSeeder::class);
        // $this->genres = Genre::factory()->count(3)->create();
        // $this->breeds = Breed::factory()->count(3)->create();
        $this->seed(GenreSeeder::class);
        $this->seed(BreedSeeder::class);
        $this->seed(VaccineSeeder::class);
        // dd(TargetPublic::where('name', 'Criança')->first()->id);
        $this->seed(TypeReasonDelayVaccineSeeder::class);
        $this->seed(ReasonDelayVaccineSeeder::class);
        $this->seed(RegionSeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CountySeeder::class);
        $this->seed(PronounSeeder::class);
        $this->seed(OccupationSeeder::class);
        $this->seed(StatusAlertSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(AccessionSeeder::class);
        // $this->url = '/api/alerts/step/vaccine-room';
        // TODO: should log with user with specific occupations
        // $occupation = Occupation::where('name', 'Técnico verificador')->first()->id;
        // $userAgenteAlerta = User::factory()->create([
        //     'occupation_id' => $occupation]
        // );
        // Sanctum::actingAs($userAgenteAlerta);
        Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );
        $deadLineSeeder = app()->make(DeadlineSeeder::class);
        $deadLineSeeder->run(Auth::user()->county_id);

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function should_add_extra_days_to_deadline_of_step_second_forwarding_based_days_interval_the_vaccine_with_lower_days_interval()
    {
        $this->markTestSkipped('resolvendo fluxo login');
        // create an tech analysis step
        // create an forwarding step
        // create an vaccine room step
        // apply 1 vacccine that has more two doses
        // check in deadline of step forwarding if has plus $x days, where $x is equals to days_interval from vaccine with lower days_interval
//        $this->generateVaccineRoomStep();

        // deduct 10 day from deadline of step forwarding
        $alertStep = AlertStep::where('status_alert_id', StatusAlert::where('name', 'encaminhamento')->first()->id)
            ->latest('id')->first();
        $alert = Alert::first();
        $deadlineEncaminhamento = Deadline::where(['name' => Deadline::ENCAMINHAMENTOS])->first()->days;
        $alert = Alert::where('id', $alert->id)->update(['created_at' => Carbon::now()->subDays($deadlineEncaminhamento)]);

        AlertStep::where('id', $alertStep->id)->update(['created_at' => Carbon::now()->subDays($deadlineEncaminhamento)]);

        // list all alerts and check if deadline of step forwarding is in term
        $response = $this->getJson('/api/records?&page=1');

        $this->assertEquals('No prazo', $response->json('data.data.0.last_step_status.prazo_analise.label'));
    }


     /**
     * baseData function
     *
     * @return array
     */
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
        $alertData['suscard'] = $this->faker->randomNumber(8, true);
        $alertData['type_status_vaccination_id'] = TypeStatusVaccination::where('name', 'Atraso Vacinal')->first()->uuid;
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
                'previous_application' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),  //Carbon::now()->toDateString(),
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

    private function generateDataAlert(): array
    {
        $alertData = $this->baseData();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('vacinacao.jpg');
        $responseUpload = $this->post('/api/upload-image', [
            'image' => $file,
        ]);
        $alertData['vaccine_card_pictures'] = array([
            'image' => $responseUpload->json()['url'],
        ]);

        $alertData['type_status_vaccination_id'] = TypeStatusVaccination::where('name', 'Atraso Vacinal')->first()->uuid;

        $vaccine = $this->faker->randomElement(Vaccine::all()->toArray());
        $alertData['vaccine_scheduled_alerts'] = [
            [
                'vaccine_id' => $vaccine['uuid'],
                'vaccination_step' => $vaccine['uuid'],
                'previous_application' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),  //Carbon::now()->toDateString(),
                'next_application' => Carbon::now()->addDay($vaccine['days_interval'])->toDateString(),
            ],
        ];

        $reasonsDelayVaccine = ReasonDelayVaccine::whereHas('TypeReasonDelayVaccine', function ($query) {
                $query->where('description', 'Atraso Vacinal');
            })->get('uuid')->toArray();

        // get 3 first elements array
        $reasonsDelayVaccine = array_slice($reasonsDelayVaccine, 0, 2);

        // remove all keys from array except uuid
        $reasonsDelayVaccine = array_map(function ($item) {
            return $item['uuid'];
        }, $reasonsDelayVaccine);

        $alertData['county_id'] = Auth::user()->county_id;
        $alertData['reasons_delay_vaccine'] = $reasonsDelayVaccine;

        return $alertData;
    }

//    private function generateTechAnalysisStep(): void
//    {
//       // create an alert_step with status_alert equals to 'Análise técnica'  referenced by record_id from the last alert created
//       $alertStep = AlertStep::create([
//        'record_id' => Alert::latest()->first()->record_id,
//        'status_alert_id' => StatusAlert::where('name', 'análise técnica')->first()->id,
//        'user_id' => Auth::user()->id,
//        ]);
//
//        // set delayed_vaccines
//        // get 3 vaccines in random order that has more than 1 dose
//        $vaccines = Vaccine::where('dose', '>', 1)->inRandomOrder()->limit(3)->get();
//        foreach($vaccines as $vaccine) {
//            DelayedVaccine::create([
//                'alert_step_id' => $alertStep->id,
//                'vaccine_id' => $vaccine->id
//            ]);
//        }
//    }

    private function generateVaccineRoomStep(): void
    {
        $alertData = $this->generateDataAlert();
        $response = $this->postJson('/api/alerts', $alertData);
//        $this->generateTechAnalysisStep();

       // create an alert_step with status_alert equals to 'Análise técnica'  referenced by record_id from the last alert created
       AlertStep::create([
        'record_id' => Alert::latest()->first()->record_id,
        'status_alert_id' => StatusAlert::where('name', 'encaminhamento')->first()->id,
        'user_id' => Auth::user()->id,
       ]);

       AlertStep::create([
        'record_id' => Alert::latest()->first()->record_id,
        'status_alert_id' => StatusAlert::where('name', 'sala de vacina')->first()->id,
        'user_id' => Auth::user()->id,
       ]);

       // remove one vaccine from delayed_vaccines
        DelayedVaccine::latest()->first()->delete();

        AlertStep::create([
            'record_id' => Alert::latest()->first()->record_id,
            'status_alert_id' => StatusAlert::where('name', 'encaminhamento')->first()->id,
            'user_id' => Auth::user()->id,
        ]);
    }
}
