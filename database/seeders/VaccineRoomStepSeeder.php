<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Alert;
use App\Models\Breed;
use App\Models\Genre;
use App\Helper\Number;
use App\Models\County;
use App\Models\Record;
use App\Models\Vaccine;
use App\Models\Accession;
use App\Models\AlertStep;
use App\Models\Occupation;
use App\Models\StatusAlert;
use Illuminate\Support\Str;
use App\Models\TargetPublic;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\VaccineCardPicture;
use Illuminate\Support\Facades\Hash;
use App\Models\TypeStatusVaccination;
use App\Models\VaccineScheduledAlert;

class VaccineRoomStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $alert = Alert::factory()->create();
        $this->call(AlertSeeder::class);


        

        $ptBrFaker = \Faker\Factory::create('pt_BR');
        $cpf = Number::onlyNumbers($ptBrFaker->cpf);
        $suscard = Number::onlyNumbers($ptBrFaker->numerify('######-##'));
        $year = rand(10, 40);
        $birthDate = Carbon::now()->subYears($year)->toDateString();
        $targetPublic = TargetPublic::first();
        $address = $this->generateAddress();

        $record = Record::create([
            'suscard' => $suscard,
            'cpf' => $cpf,
        ]);

        Alert::create([
            'record_id' => $record->id,
            'user_id' => User::where('occupation_id', Occupation::COORDENADOR_OPERACIONAL_SAUDE)->first()->id,//Coordenador Operacional da saúde
            'target_public_id' => $targetPublic->id,
            'name' => $ptBrFaker->name(),
            'cpf' => $cpf,
            'rg' => str_replace(['.','-'], ['',''], $ptBrFaker->rg),
            'birthdate' => $this->generateBirthdate($targetPublic->id),
            'email' => '',
            'phone' => '',
            'mobilephone' => '',
            'breed_id' => Breed::inRandomOrder()->first()->id,
            'genre_id' => Genre::inRandomOrder()->first()->id,
            'suscard' => $suscard,
            'type_status_vaccination_id' => TypeStatusVaccination::where('name', 'Atraso Vacinal')->id,
            'mother_name' => $ptBrFaker->name(),
            'mother_email' => $ptBrFaker->email,
            'mother_cpf' => str_replace(['.','-'], ['',''], $ptBrFaker->cpf),
            'mother_rg' => str_replace(['.','-'], ['',''], $ptBrFaker->rg),
            'mother_phone' => str_replace(['(',')','-'], ['','',''], sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)),
            'mother_mobilephone' => sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline),
            'father_name' => $ptBrFaker->name(),
            'father_email' => $ptBrFaker->email,
            'father_cpf' => str_replace(['.','-'], ['',''], $ptBrFaker->cpf),
            'father_rg' => str_replace(['.','-'], ['',''], $ptBrFaker->rg),
            'father_phone' => str_replace(['(',')','-'], ['','',''], sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)),
            'father_mobilephone' => sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline),
            'postalcode' =>  $address['postalcode'], //'41610-000',
            'street' => $address['street'], //'R. Antônino Casaes', //$ptBrFaker->address(),
            'state' => $address['state'], //'BA', //$ptBrFaker->stateAbbr(),
            'city' => $address['city'], //'Salvador', //$ptBrFaker->city(),
            'district' => $address['district'], //'Itapuã',//$ptBrFaker->name(),
            'bae' => '1',
            'visit_date' => $ptBrFaker->dateTimeBetween('-1 years', 'now'),
            'comments' => 'criança mora com os avós maternos',
            'is_visit' => 1,
            'county_id' => County::latestSeederId()
        ]);

        $vaccine = Vaccine::where('name', '=', 'Poliomielite (VIP)')->first();

        VaccineScheduledAlert::create([
            'alert_id' => Alert::latest()->first()->id,
            'vaccine_id' => $vaccine->id,
            'vaccination_step' => '',
            'previous_application' => Carbon::now()->toDateString(),
            'next_application' => Carbon::now()->addDay($vaccine->days_interval)->toDateString(),
        ]);

        VaccineCardPicture::create([
            'alert_id' => Alert::latest()->first()->id,
            'image' => 'https://www.uruguaiana.rs.leg.br/comunicacoes/noticias/apresentar-carteira-de-vacinacao-na-matricula-sera-obrigatorio/@@images/d0f83dbd-de72-491d-a5f6-8ea22ab0a07f.jpeg',
        ]);
    }

    
}
