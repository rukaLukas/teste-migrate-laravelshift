<?php

namespace Database\Factories;

use App\Helper\Number;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Breed;
use App\Models\Genre;
use App\Models\TypeAlerts;
use App\Models\TargetPublic;
use App\Models\TypeStatusVaccination;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ptBrFaker = \Faker\Factory::create('pt_BR');
        $year = rand(10, 40);
        $birthDate = Carbon::now()->subYears($year)->toDateString();
        $targetPublic = TargetPublic::first();
        $vaccineCardPicture = 'https://www.uruguaiana.rs.leg.br/comunicacoes/noticias/apresentar-carteira-de-vacinacao-na-matricula-sera-obrigatorio/@@images/d0f83dbd-de72-491d-a5f6-8ea22ab0a07f.jpeg';
        return [
            'user_id' => 1,
            'target_public_id' => $targetPublic,
            'name' => $this->faker->name(),
            'cpf' => Number::onlyNumbers($ptBrFaker->cpf),
            'rg' => Number::onlyNumbers($ptBrFaker->rg),
            'birthdate' => $this->generateBirthdate($targetPublic->id),
            'email' => '',
            'phone' => '',
            'mobilephone' => '',
            'breed_id' => Breed::inRandomOrder()->first()->id,
            'genre_id' => Genre::inRandomOrder()->first()->id,
            'suscard' => Number::onlyNumbers($this->faker->numerify('######-##')),
            'type_status_vaccination_id' => TypeStatusVaccination::first()->id,
            // 'vaccine_scheduled_alerts' => '',
            'mother_name' => $this->faker->name(),
            'mother_email' => $this->faker->email,
            'mother_cpf' => Number::onlyNumbers($ptBrFaker->cpf),
            'mother_rg' => Number::onlyNumbers($ptBrFaker->rg),
            'mother_phone' => str_replace(['(',')','-'], ['','',''], sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)),
            'mother_mobilephone' => sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline),
            'father_name' => $this->faker->name(),
            'father_email' => $this->faker->email(),
            'father_cpf' => Number::onlyNumbers($ptBrFaker->cpf),
            'father_rg' => Number::onlyNumbers($ptBrFaker->rg),
            'father_phone' => str_replace(['(',')','-'], ['','',''], sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline)),
            'father_mobilephone' => sprintf('%s%s', $ptBrFaker->areaCode, $ptBrFaker->landline),
            'postalcode' => $ptBrFaker->postcode(),
            'street' => $ptBrFaker->address(),
            'state' => $ptBrFaker->stateAbbr(),
            'city' => $ptBrFaker->city(),
            'district' => $ptBrFaker->neighborhood(),
            'reason_not_has_vac_card_pic' => '',//rand(1,2),
            // 'vaccine_card_pictures' => '',
            'bae' => '1',
            'visit_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'comments' => $this->faker->sentence(25),
            'is_visit' => 1
        ];        
    }

    public function generateBirthdate(int $targetPublicId)
    {
        $year = rand(14, 40);
        $birthDate = $targetPublicId == 1 ? Carbon::now()->subYears(10)->toDateString() : Carbon::now()->subYears($year)->toDateString();
        return $birthDate;
    }    
}
