<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Alert;
use App\Models\Breed;
use App\Models\Genre;
use App\Helper\Number;
use App\Models\County;
use App\Models\Record;
use App\Models\Profile;
use App\Models\Pronoun;
use App\Models\Vaccine;
use App\Models\Deadline;
use App\Models\Accession;
use App\Models\AlertStep;
use App\Models\Occupation;
use App\Models\StatusAlert;
use Illuminate\Support\Str;
use App\Models\TargetPublic;
use Laravel\Sanctum\Sanctum;
use App\Models\DelayedVaccine;
use Illuminate\Support\Carbon;
use Tests\RefreshTestDatabase;
use App\Models\GovernmentAgency;
use App\Models\GovernmentOffice;
use App\Models\ReasonCloseAlert;
use Database\Seeders\UserSeeder;
use Database\Seeders\BreedSeeder;
use Database\Seeders\GenreSeeder;
use Database\Seeders\StateSeeder;
use Illuminate\Http\UploadedFile;
use App\Models\ReasonDelayVaccine;
use App\Models\VaccineCardPicture;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\PronounSeeder;
use Database\Seeders\VaccineSeeder;
use Database\Factories\VisitFactory;
use Database\Seeders\DeadlineSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\TypeStatusVaccination;
use Database\Seeders\AccessionSeeder;
use Database\Seeders\AlertStepSeeder;
use Database\Seeders\OccupationSeeder;
use Database\Seeders\StatusAlertSeeder;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AbstractResource;
use Database\Seeders\TargetPublicSeeder;
use Database\Seeders\GovernmentOfficeSeeder;
use Database\Seeders\ReasonCloseAlertSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Database\Seeders\ReasonDelayVaccineSeeder;
use Database\Seeders\TypeStatusVaccinationSeeder;
use Database\Seeders\TypeReasonDelayVaccineSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Resources\Configuration\GovernmentOfficeResource;

class AlertTest extends TestCase
{
    // use RefreshTestDatabase;
    use RefreshDatabase;
    // use DatabaseMigrations;
    use WithFaker;

    protected $targetPublic;
    protected $typeStatusVaccination;
    protected $genres;
    protected $breeds;
    protected $reasonDelayVaccine;
    protected $alert;

    public function setup(): void
    {
        // $this->markTestSkipped('Refatorar is_visit');
        parent::setUp();

        $this->url = '/api/alerts';
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
        GovernmentAgency::factory()->count(3)->create();
        Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );                         
        $this->seed(AccessionSeeder::class);
        $this->alert = Alert::factory()->create();        
        // $this->withoutExceptionHandling();
    }

    /** @test */
    public function should_create_visit()
    {    
        $alertData = $this->generateDataVisit();

        $response = $this->postJson($this->url, $alertData);

        $response->assertStatus(201);

        $alert = Alert::all()[1];

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data.response', 54)
                    ->where('data.response.who.id', User::find($alert->user_id)->uuid)
                    ->where('data.response.target_public.id', $alertData['target_public_id'])
                    ->where('data.response.type_status_vaccination.id', $alertData['type_status_vaccination_id'])
                    ->has('data.response.vaccine_scheduled_alerts', null, fn ($json) =>
                        $json->has('id')
                            ->where('alert_id', $alert->uuid)
                            ->where('vaccine.id', $alertData['vaccine_scheduled_alerts'][0]['vaccine_id'])
                            ->where('previous_application', $alertData['vaccine_scheduled_alerts'][0]['previous_application'])
                            ->where('next_application', $alertData['vaccine_scheduled_alerts'][0]['next_application'])
                            ->etc()
                    )
                    ->where('data.response.name', $alertData['name'])
                    ->where('data.response.cpf', $this->mask($alertData['cpf'],'###.###.###-##'))
                    ->where('data.response.rg', $this->mask($alertData['rg'],'##.###.####-##'))
                    ->where('data.response.birthdate', Carbon::createFromFormat('Y-m-d', $alertData['birthdate'])->format('Y-m-d'))
                    ->where('data.response.birthdate_formatted', Carbon::createFromFormat('Y-m-d', $alertData['birthdate'])->format('d/m/Y'))
                    ->where('data.response.suscard', $this->mask($alertData['suscard'],'######-##'))
                    ->where('data.response.mother_name', $alertData['mother_name'])
                    ->where('data.response.mother_email', $alertData['mother_email'])
                    ->where('data.response.mother_cpf', $this->mask($alertData['mother_cpf'],'###.###.###-##'))
                    ->where('data.response.mother_rg', $this->mask($alertData['mother_rg'],'##.###.####-##'))
                    ->where('data.response.mother_phone', $this->mask($alertData['mother_phone'],'(##) ####-####'))
                    ->where('data.response.mother_mobilephone', $this->mask($alertData['mother_mobilephone'], '(##) #####-####'))
                    ->where('data.response.father_name', $alertData['father_name'])
                    ->where('data.response.father_email', $alertData['father_email'])
                    ->where('data.response.father_cpf', $this->mask($alertData['father_cpf'], '###.###.###-##'))
                    ->where('data.response.father_rg', $this->mask($alertData['father_rg'], '##.###.####-##'))
                    ->where('data.response.father_phone', $this->mask($alertData['father_phone'], '(##) ####-####'))
                    ->where('data.response.father_mobilephone', $this->mask($alertData['father_mobilephone'], '(##) #####-####'))
                    ->where('data.response.postalcode', $this->mask($alertData['postalcode'],'#####-###'))
                    ->where('data.response.street', $alertData['street'])
                    ->where('data.response.state', $alertData['state'])
                    ->where('data.response.city', $alertData['city'])
                    ->where('data.response.district', $alertData['district'])
                    ->where('data.response.reasons_delay_vaccine', [])
                    ->where('data.response.reason_not_has_vac_card_pic', null)
                    ->where('data.response.vaccine_card_pictures.0.image', VaccineCardPicture::where('alert_id', $alert->id)->first()->image)
                    ->where('data.response.bae', (integer)$alertData['bae'])
                    ->where('data.response.visit_date', Carbon::createFromFormat('Y-m-d', $alertData['visit_date'])->format('Y-m-d'))
                    ->where('data.response.visit_date_formatted', Carbon::createFromFormat('Y-m-d', $alertData['visit_date'])->format('d/m/Y'))
                    ->where('data.response.comments', $alertData['comments'])
                    ->where('data.response.is_visit', 1)
                    ->where('data.response.prazo', '')
                    ->where('data.response.status', Alert::where('cpf', $alertData['cpf'])->count() . 'ª visita')
                    ->where('data.response.etapa', 'Visita')
                    ->etc()
            );
    }

    /** @test */
    public function should_create_alert()
    {        
        $alertData = $this->generateDataAlert();        
        
        $response = $this->postJson($this->url, $alertData);

        $response->assertStatus(201);

        $alert = Alert::all()[1];

        // get from ReasonDelayVaccine where uuid is in $alertData['reasons_delay_vaccine'] and map to array only fields: description, uuid, is_send_social_assistence
        $reasonsDelayVaccine = ReasonDelayVaccine::whereIn('uuid', $alertData['reasons_delay_vaccine'])->get()->map(function ($item, $key) {
            return [
                'id' => $item->uuid,
                'target_public' => null,
                'description' => $item->description,
                'is_send_social_assistence' => false,
                'forwarding' => GovernmentOfficeResource::collection($item->governmentOffices)->toArray('id'),
            ];
        })->toArray();


        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data.response', 54)
                    ->where('data.response.who.id', User::find($alert->user_id)->uuid)
                    ->where('data.response.target_public.id', $alertData['target_public_id'])
                    ->where('data.response.type_status_vaccination.id', $alertData['type_status_vaccination_id'])
                    ->has('data.response.vaccine_scheduled_alerts', null, fn ($json) =>
                        $json->has('id')
                            ->where('alert_id', $alert->uuid)
                            ->where('vaccine.id', $alertData['vaccine_scheduled_alerts'][0]['vaccine_id'])
                            ->where('previous_application', $alertData['vaccine_scheduled_alerts'][0]['previous_application'])
                            ->where('next_application', $alertData['vaccine_scheduled_alerts'][0]['next_application'])
                            ->etc()
                    )
                    ->where('data.response.name', $alertData['name'])
                    ->where('data.response.cpf', $this->mask($alertData['cpf'], '###.###.###-##'))
                    ->where('data.response.rg', $this->mask($alertData['rg'], '##.###.####-##'))
                    ->where('data.response.birthdate', Carbon::createFromFormat('Y-m-d', $alertData['birthdate'])->format('Y-m-d'))
                    ->where('data.response.birthdate_formatted', Carbon::createFromFormat('Y-m-d', $alertData['birthdate'])->format('d/m/Y'))
                    ->where('data.response.suscard', $this->mask($alertData['suscard'], '######-##'))
                    ->where('data.response.mother_name', $alertData['mother_name'])
                    ->where('data.response.mother_email', $alertData['mother_email'])
                    ->where('data.response.mother_cpf', $this->mask($alertData['mother_cpf'], '###.###.###-##'))
                    ->where('data.response.mother_rg', $this->mask($alertData['mother_rg'], '##.###.####-##'))
                    ->where('data.response.mother_phone', $this->mask($alertData['mother_phone'], '(##) ####-####'))
                    ->where('data.response.mother_mobilephone', $this->mask($alertData['mother_mobilephone'], '(##) #####-####'))
                    ->where('data.response.father_name', $alertData['father_name'])
                    ->where('data.response.father_email', $alertData['father_email'])
                    ->where('data.response.father_cpf', $this->mask($alertData['father_cpf'], '###.###.###-##'))
                    ->where('data.response.father_rg', $this->mask($alertData['father_rg'], '##.###.####-##'))
                    ->where('data.response.father_phone', $this->mask($alertData['father_phone'], '(##) ####-####'))
                    ->where('data.response.father_mobilephone', $this->mask($alertData['father_mobilephone'], '(##) #####-####'))
                    ->where('data.response.postalcode', $this->mask($alertData['postalcode'], '#####-###'))
                    ->where('data.response.street', $alertData['street'])
                    ->where('data.response.state', $alertData['state'])
                    ->where('data.response.city', $alertData['city'])
                    ->where('data.response.district', $alertData['district'])
                    ->where('data.response.reasons_delay_vaccine', $reasonsDelayVaccine)
                    ->where('data.response.reason_not_has_vac_card_pic', null)
                    ->where('data.response.vaccine_card_pictures.0.image', VaccineCardPicture::where('alert_id', $alert->id)->first()->image)
                    ->where('data.response.bae', (integer)$alertData['bae'])
                    ->where('data.response.visit_date', Carbon::createFromFormat('Y-m-d', $alertData['visit_date'])->format('Y-m-d'))
                    ->where('data.response.comments', $alertData['comments'])
                    ->where('data.response.is_visit', 0)
                    ->where('data.response.is_alert', 1)
                    ->where('data.response.prazo', '')
                    ->where('data.response.status', 'Pendente de Análise')
                    ->where('data.response.etapa', 'Alerta')
                    ->etc()
            );
    }

     /** @test */
     public function should_return_status_422_when_create_visit_with_reasons_delay_vaccine()
     {
         $alertData = $this->generateDataVisit();
         for ($i = 0; $i < $this->faker->numberBetween(1, 6); $i++) {
             $alertData['reasons_delay_vaccine'][] = ReasonDelayVaccine::inRandomOrder()->first()->id;
         }

         $response = $this->postJson($this->url, $alertData);

         $response->assertStatus(422);

         $response->assertJsonValidationErrors([
             'reasons_delay_vaccine'
         ]);
     }

      /** @test */
    public function should_return_status_422_when_create_visit_without_required_data()
    {
        $alertData = $this->generateDataVisit();
        $fields = [
            'name',
            'cpf',
            'birthdate',
            'suscard',
            'type_status_vaccination_id',
            // 'mother_name',
            'mother_email',
            'mother_cpf',
            'mother_rg',
            'mother_phone',
            'father_name',
            'father_email',
            'father_cpf',
            'father_rg',
            'father_phone',
            'father_mobilephone',
            'postalcode',
            'street',
            'state',
            'city',
            'district',
            'bae',
        ];
        foreach ($fields as $field) {
            $alertData[$field] = '';
        }

        $response = $this->postJson($this->url, $alertData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'name',
            'birthdate',
            'suscard',
            'type_status_vaccination_id',
            // 'mother_name',
            'postalcode',
            'street',
            'state',
            'city',
            'district'
        ]);
    }

    /** @test */
    public function should_return_status_422_when_create_visit_with_incorrect_data()
    {
        $alertData = $this->generateDataVisit();
        $alertData['target_public_id'] = $this->faker->uuid();
        $alertData['name'] = $this->faker->lexify('????');
        $alertData['cpf'] = $this->faker->randomNumber(9, true);
        $alertData['rg'] = $this->faker->randomNumber(6, true);
        // data no futuro
        $alertData['birthdate'] = $this->faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d');
        $alertData['suscard'] = $this->faker->randomNumber(9, true);
        $alertData['type_status_vaccination_id'] = $this->faker->randomNumber(2, true);
        $alertData['mother_name'] = $this->faker->lexify('????');
        $alertData['mother_email'] = $this->faker->lexify('????');
        $alertData['mother_cpf'] = $this->faker->randomNumber(9, true);
        $alertData['mother_rg'] = $this->faker->randomNumber(6, true);
        $alertData['mother_phone'] = $this->faker->randomNumber(9, true);
        $alertData['father_name'] = $this->faker->lexify('????');
        $alertData['father_email'] = $this->faker->lexify('????');
        $alertData['father_cpf'] = $this->faker->randomNumber(9, true);
        $alertData['father_rg'] = $this->faker->randomNumber(6, true);
        $alertData['postalcode'] = $this->faker->randomNumber(7, true);
        $alertData['street'] = $this->faker->lexify('????');
        $alertData['state'] = $this->faker->lexify('???');
        $alertData['city'] = '';
        $alertData['district'] = '';
        $alertData['bae'] = $this->faker->lexify('?');
        $alertData['vaccine_scheduled_alerts'] = [];

        $response = $this->postJson($this->url, $alertData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'target_public_id',
            'name',
            'rg',
            'birthdate',
            'suscard',
            'type_status_vaccination_id',
            // 'mother_name',
            'mother_cpf',
            'father_name',
            'postalcode',
            'street',
            'state',
            'city',
            'district',
            'vaccine_scheduled_alerts'
        ]);
    }

    /** @test */
    public function should_close_alert()
    {
        // criar alerta
        $alertData = $this->generateDataAlert();
        
        $response = $this->postJson($this->url, $alertData);

        $response->assertStatus(201);

        // encerrar alerta
        $recordId = $response->json('data.response.record_id');
        $this->seed(ReasonCloseAlertSeeder::class);
        $response = $this->postJson('/api/closed-alerts', [
            'user_id' => Auth::user()->id,
            'record_id' => $recordId,
            'reason_close_alert_id' => ReasonCloseAlert::inRandomOrder()->first()->id,
            'description' => "",
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('alert_steps', [
            'record_id' => Record::findByUuid($recordId)->id,
            'status_alert_id' => StatusAlert::where('name', 'Encerrado')->first()->id,
        ]);
    }


    /** @test */
    public function should_return_status_422_when_trying_access_alert_that_not_exists()
    {
        $data = $this->generateDataVisit();
        $id = $this->faker->uuid();

        $response = $this->get("{$this->url}/{$id}", $data);

        $response->assertStatus(422);

        $response->assertJson(array('errors' => [
            'Não existe registro com o código ' . $id,
        ]));
    }

    /** @test */
    public function should_mark_all_vaccines_as_not_applied_when_vaccination_status_equals_not_vaccinated_or_not_has_card_vaccine()
    {
        $data = $this->generateDataAlert();
        $nameTypeStatusVaccination = ['Não vacinada', 'Sem carteirinha'];
        $data['type_status_vaccination_id'] = TypeStatusVaccination::where('name', $nameTypeStatusVaccination[rand(0, 1)])->first()->uuid;
        $response = $this->post("{$this->url}", $data);

        $response->assertStatus(201);
    }

    /** @test */
    public function should_create_alert_step_forwarding_without_technical_analysis_when_vaccination_status_equals_not_vaccinated_or_not_has_card_vaccine()
    {
        $data = $this->generateDataAlert();
        $nameTypeStatusVaccination = ['Não vacinada', 'Sem carteirinha'];
        $data['type_status_vaccination_id'] = TypeStatusVaccination::where('name', $nameTypeStatusVaccination[rand(0, 1)])->first()->uuid;

        $response = $this->post("{$this->url}", $data);

        $alertStepForwarding = AlertStep::where('status_alert_id', StatusAlert::where('name', 'encaminhamento')->first()->id)->count();
        $this->assertEquals(1, $alertStepForwarding);
    }

     /** @test */
     public function should_list_delayed_vaccines_when_create_alert_with_vaccination_status_equals_not_vaccinated_or_not_has_card_vaccine()
     {
         $data = $this->generateDataAlert();
         $nameTypeStatusVaccination = ['Não vacinada', 'Sem carteirinha'];
         $data['type_status_vaccination_id'] = TypeStatusVaccination::where('name', $nameTypeStatusVaccination[rand(0, 1)])->first()->uuid;

         $response = $this->postJson("{$this->url}", $data);
         $recordId = $response->json('data.response.record_id');

         $response = $this->getJson("api/records/{$recordId}");
         $genre = Genre::where('id', $data['genre_id'])->first()->name;
         $genre = $genre == 'Masculino' ? 'M' : 'F';
         $vaccines = Vaccine::where('target_public_id', TargetPublic::findByUUID($data['target_public_id'])->id)
            ->whereIn('genre', ['M/F', $genre])
            ->count();

         $this->assertEquals(count($response->json('data.delayed_vaccines')), $vaccines);
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

    private function generateDataVisit(): array
    {
        return $this->baseData();
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
}
