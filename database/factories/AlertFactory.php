<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Alert;
use App\Models\Breed;
use App\Models\Genre;
use App\Helper\Number;
use App\Models\County;
use App\Models\Record;
use App\Models\Vaccine;
use App\Models\TypeAlerts;
use App\Models\TargetPublic;
use App\Models\VaccineCardPicture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeStatusVaccination;
use App\Models\VaccineScheduledAlert;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{

    public function configure()
    {
        return $this->afterCreating(function (Alert $alert) {
            // $vaccine = Vaccine::find(rand(1,5));
            $vaccine = Vaccine::inRandomOrder()->first();
            if ($alert->is_visit == 1) {
                VaccineCardPicture::create([
                    'alert_id' => $alert->id,
                    'image' => 'https://www.uruguaiana.rs.leg.br/comunicacoes/noticias/apresentar-carteira-de-vacinacao-na-matricula-sera-obrigatorio/@@images/d0f83dbd-de72-491d-a5f6-8ea22ab0a07f.jpeg',
                ]);
            }

            DB::table('vaccine_scheduled_alerts')->insert([
                'vaccine_id' => $vaccine->id,
                'alert_id' => $alert->id,
                'vaccination_step' => '',
                'previous_application' => Carbon::now()->toDateString(),
                'next_application' => Carbon::now()->addDay($vaccine->days_interval)->toDateString(),
                'uuid' => $this->faker->uuid(),
            ]);

            // TODO implementar o reason delay vaccine quando for caso de um alerta de atraso
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // dump("definition");
        $ptBrFaker = \Faker\Factory::create('pt_BR');
        $year = rand(10, 40);
        $birthDate = Carbon::now()->subYears($year)->toDateString();
        $targetPublic = TargetPublic::inRandomOrder()->first();
        $typeStatusVaccination = TypeStatusVaccination::inRandomOrder()->first();
        $record = Record::factory()->create();

        return [
            'record_id' => $record->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'target_public_id' => $targetPublic->id,
            'name' => $this->faker->name(),
            'cpf' => $record->cpf,
            'rg' => Number::onlyNumbers($ptBrFaker->rg),
            'birthdate' => $this->generateBirthdate($targetPublic),
            'email' => $targetPublic->id != 1 ? $this->faker->email() :'',
            'phone' => $targetPublic->id != 1 ? Number::onlyNumbers(sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)) : '',
            'mobilephone' => '',
            'breed_id' => Breed::inRandomOrder()->first()->id,
            'genre_id' => Genre::inRandomOrder()->first()->id,
            'suscard' => $record->suscard,
            'type_status_vaccination_id' => $typeStatusVaccination->id,
            // 'vaccine_scheduled_alerts' => '',
            'mother_name' => $this->faker->name(),
            'mother_email' => $this->faker->email,
            'mother_cpf' => Number::onlyNumbers($ptBrFaker->cpf),
            'mother_rg' => Number::onlyNumbers($ptBrFaker->rg),
            'mother_phone' => Number::onlyNumbers(sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)), //str_replace(['(',')','-'], ['','',''], sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)),
            'mother_mobilephone' => Number::onlyNumbers(sprintf('%s9%s', $ptBrFaker->areaCode, $ptBrFaker->landline)),
            'father_name' => $this->faker->name(),
            'father_email' => $this->faker->email(),
            'father_cpf' => Number::onlyNumbers($ptBrFaker->cpf),
            'father_rg' => Number::onlyNumbers($ptBrFaker->rg),
            'father_phone' => str_replace(['(',')','-'], ['','',''], sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)),
            'father_mobilephone' => sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline),
            'postalcode' => $ptBrFaker->postcode(),
            'street' => $ptBrFaker->streetName(),
            'state' => $ptBrFaker->stateAbbr(),
            'city' => $ptBrFaker->city(),
            'district' => $ptBrFaker->secondaryAddress(),
            'reason_not_has_vac_card_pic' => $typeStatusVaccination->name != "Vacinação em dia" ? $this->faker->numberBetween(1, 2) : '',
            // 'vaccine_card_pictures' => '',
            'bae' => $this->faker->numberBetween(0, 1),
            'visit_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'comments' => $this->faker->sentence(25),
            'is_visit' => $typeStatusVaccination->name != "Vacinação em dia" ? 0 : 1,
            'is_alert' =>  $typeStatusVaccination->name != "Vacinação em dia" ? 1 : 0,
            'county_id' => County::latestSeederId()
        ];

        // VaccineScheduledAlert::create([
        //     'vaccine_id' => 1,
        //     'alert_id' => 1,//Alert::latest()->first()->id,
        //     'vaccination_step' => '',
        //     'previous_application' => Carbon::now()->toDateString(),
        //     'next_application' => Carbon::now()->toDateString(),
        // ]);
    }

    public function generateBirthdate(mixed $targetPublic)
    {
        $year = rand(14, 40);
        $birthDate = $targetPublic->name == 'Criança' ? Carbon::now()->subYears(env('AGE_CHILD'))->toDateString() : Carbon::now()->subYears($year)->toDateString();
        return $birthDate;
    }

    private function generateBaseDataAlert()
    {
        $ptBrFaker = \Faker\Factory::create('pt_BR');
        $year = rand(10, 40);
        $birthDate = Carbon::now()->subYears($year)->toDateString();
        $targetPublic = TargetPublic::all();
        $vaccineCardPicture = 'https://www.uruguaiana.rs.leg.br/comunicacoes/noticias/apresentar-carteira-de-vacinacao-na-matricula-sera-obrigatorio/@@images/d0f83dbd-de72-491d-a5f6-8ea22ab0a07f.jpeg';
        return [
            'user_id' => 1,
            'target_public_id' => $targetPublic,
            'name' => $this->faker->name(),
            'cpf' => str_replace(['.','-'], ['',''], $ptBrFaker->cpf),
            'rg' => str_replace(['.','-'], ['',''], $ptBrFaker->rg),
            'birthdate' => $this->generateBirthdate($targetPublic),
            'email' => '',
            'phone' => '',
            'mobilephone' => '',
            'breed_id' => Breed::inRandomOrder()->first()->id,
            'genre_id' => Genre::inRandomOrder()->first()->id,
            'suscard' => str_replace(['.','-'], ['',''], $this->faker->numerify('######-##')),
            'type_status_vaccination_id' => TypeStatusVaccination::first()->id,
            // 'vaccine_scheduled_alerts' => '',
            'mother_name' => $this->faker->name(),
            'mother_email' => $this->faker->email,
            'mother_cpf' => str_replace(['.','-'], ['',''], $ptBrFaker->cpf),
            'mother_rg' => str_replace(['.','-'], ['',''], $ptBrFaker->rg),
            'mother_phone' => str_replace(['(',')','-'], ['','',''], sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)),
            'mother_mobilephone' => sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline),
            'father_name' => $this->faker->name(),
            'father_email' => $this->faker->email(),
            'father_cpf' => str_replace(['.','-'], ['',''], $ptBrFaker->cpf),
            'father_rg' => str_replace(['.','-'], ['',''], $ptBrFaker->rg),
            'father_phone' => str_replace(['(',')','-'], ['','',''], sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)),
            'father_mobilephone' => sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline),
            'postalcode' => '41610-000',
            'street' => 'R. Antônino Casaes', //$ptBrFaker->address(),
            'state' => 'BA', //$ptBrFaker->stateAbbr(),
            'city' => 'Salvador', //$ptBrFaker->city(),
            'district' => 'Itapuã',//$this->faker->name(),
            'reason_not_has_vac_card_pic' => '',//rand(1,2),
            // 'vaccine_card_pictures' => '',
            'bae' => '1',
            'visit_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'comments' => $this->faker->sentence(25),
            'is_visit' => $this->faker->numberBetween(0,1),
            'county_id' => County::latestSeederId()
        ];
    }
}
