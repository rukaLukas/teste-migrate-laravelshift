<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\County;
use App\Models\Profile;
use App\Models\Accession;
use App\Models\Occupation;
use Illuminate\Support\Str;
use Tests\RefreshTestDatabase;
use Database\Seeders\UserSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Database\Seeders\PronounSeeder;
use Database\Seeders\AccessionSeeder;
use Database\Seeders\OccupationSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LoginTest extends TestCase
{
    use RefreshTestDatabase;
    use WithFaker;
    use HasFactory;

    const ROUTE_PASSWORD_EMAIL = 'password.email';
    const ROUTE_PASSWORD_RESET = 'recover.password';
    const ROUTE_PASSWORD_RESET_SUBMIT = 'password.reset';

    const USER_ORIGINAL_PASSWORD = '#secret20';

    private $password;
    
    public function setup(): void
    {
        parent::setUp();
        Profile::factory()->count(3)->create();
        $this->seed(PronounSeeder::class);
        $this->seed(OccupationSeeder::class);
        $this->seed(RegionSeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CountySeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(AccessionSeeder::class);
        $this->password = Str::random(12);
        $this->app->setLocale("pt-BR");
        $this->url = '/api/auth/login';
        // $this->withoutExceptionHandling();
    }

    /** @test */
    public function should_return_status_422_when_county_has_any_pendency()
    {
        // prepare data
        $accessionJourney = $this->accessionJourney();
        $accession = Accession::where('county_id', $accessionJourney['userGestorPolitico']->county_id)->first();
        $itemsStatus = ['status_gestor_politico', 'status_prefeito'];
        //choice random status
        $randomStatus = $itemsStatus[array_rand($itemsStatus)];
        $accession[$randomStatus] = 'pendente';
        $accession->save();       
        $this->setCustomPassword($accessionJourney['userGestorPolitico']->email);
       
        $response = $this->postJson($this->url, [
            "email" => $accessionJourney['userGestorPolitico']->email,
            "password" => self::USER_ORIGINAL_PASSWORD
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'status_accession' => [
                'Município possui pendência na situação cadastral.',               
            ]           
        ]);       
    }

    /** @test */
    public function should_return_status_422_when_user_is_not_bind_to_county()
    {
        // prepare data
        $accessionJourney = $this->accessionJourney();
        $accession = Accession::where('county_id', $accessionJourney['userGestorPolitico']->county_id)->first();
        
        $user = User::where('email', $accessionJourney['userGestorPolitico']->email)->first();
        $user->county_id = County::where('id', '!=', $accessionJourney['userGestorPolitico']->county_id)->first()->id;
        $user->save();

        $itemsStatus = ['status_gestor_politico', 'status_prefeito'];
        //choice random status
        $randomStatus = $itemsStatus[array_rand($itemsStatus)];
        $accession[$randomStatus] = 'pendente';
        $accession->save();       
        $this->setCustomPassword($accessionJourney['userGestorPolitico']->email);
       
        $response = $this->postJson($this->url, [
            "email" => $accessionJourney['userGestorPolitico']->email,
            "password" => self::USER_ORIGINAL_PASSWORD
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'status' => [
                'Usuário sem vinculo com município ativo.',               
            ]           
        ]);       
    }

    /** @test */
    public function should_login()
    {
        $accessionJourney = $this->accessionJourney();
        $this->setCustomPassword($accessionJourney['userGestorPolitico']->email);

        $response = $this->postJson($this->url, [
            "email" => $accessionJourney['userGestorPolitico']->email,
            "password" => self::USER_ORIGINAL_PASSWORD
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function should_login_as_any_occupation()
    {
        $accessionJourney = $this->accessionJourney();
        // create users with county_id equals county_id of userPrefeito
        // get all occupations except gestor_politico and prefeito
        Occupation::where('name', '!=', 'gestor_politico')
            ->where('name', '!=', 'prefeito')->get()
            ->each(function ($occupation) use ($accessionJourney) {
                $user = User::factory()->count(1)->create([
                    'occupation_id' => $occupation->id,
                    'county_id' => $accessionJourney['userPrefeito']->county_id
                ]);
                $this->setCustomPassword($user->first()->email);

                $response = $this->postJson($this->url, [
                    "email" => $user->first()->email,
                    "password" => self::USER_ORIGINAL_PASSWORD
                ]);
        
                $response->assertStatus(200);
            });                  
    }

    private function setCustomPassword(string $email)
    {
        $user = User::where('email', $email)->first();
        $user->password = bcrypt(self::USER_ORIGINAL_PASSWORD);
        $user->save();
    }
}
