<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Alert;
use App\Models\Breed;
use App\Models\Genre;
use App\Models\Vaccine;
use Illuminate\Support\Str;
use App\Models\TargetPublic;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\VaccineCardPicture;
use Illuminate\Support\Facades\Hash;
use App\Models\TypeStatusVaccination;
use App\Models\VaccineScheduledAlert;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Alert::factory()->count(2)->create();

        $ptBrFaker = \Faker\Factory::create('pt_BR');
        $year = rand(10, 40);
        $birthDate = Carbon::now()->subYears($year)->toDateString();
        $targetPublic = TargetPublic::first();

        Alert::create([
            'user_id' => User::inRandomOrder()->first()->id,
            'target_public_id' => $targetPublic->id,
            'name' => $ptBrFaker->name(),
            'cpf' => str_replace(['.','-'], ['',''], $ptBrFaker->cpf),
            'rg' => str_replace(['.','-'], ['',''], $ptBrFaker->rg),
            'birthdate' => $this->generateBirthdate($targetPublic->id),
            'email' => '',
            'phone' => '',
            'mobilephone' => '',
            'breed_id' => Breed::inRandomOrder()->first()->id,
            'genre_id' => Genre::inRandomOrder()->first()->id,
            'suscard' => str_replace(['.','-'], ['',''], $ptBrFaker->numerify('######-##')),
            'type_status_vaccination_id' => TypeStatusVaccination::first()->id,            
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
            'postalcode' => '41610-000',
            'street' => 'R. Antônino Casaes', //$ptBrFaker->address(),
            'state' => 'BA', //$ptBrFaker->stateAbbr(),
            'city' => 'Salvador', //$ptBrFaker->city(),
            'district' => 'Itapuã',//$ptBrFaker->name(),
            'bae' => '1',
            'visit_date' => $ptBrFaker->dateTimeBetween('-1 years', 'now'),
            'comments' => 'criança mora com os avós maternos',
            'is_visit' => 1
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

    public function generateBirthdate(int $targetPublicId)
    {
        $year = rand(14, 40);
        $birthDate = $targetPublicId == 1 ? Carbon::now()->subYears(10)->toDateString() : Carbon::now()->subYears($year)->toDateString();
        return $birthDate;
    }
}
