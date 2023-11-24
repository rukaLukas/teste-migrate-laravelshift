<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Pronoun;
use Illuminate\Support\Str;
use Tests\RefreshTestDatabase;
use Database\Seeders\StateSeeder;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Database\Seeders\PronounSeeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\OccupationSeeder;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class RecoverPasswordTest extends TestCase
{
    use RefreshTestDatabase;
    use WithFaker;
    use HasFactory;

    const ROUTE_PASSWORD_EMAIL = 'password.email';
    const ROUTE_PASSWORD_RESET = 'recover.password';
    const ROUTE_PASSWORD_RESET_SUBMIT = 'password.reset';

    const USER_ORIGINAL_PASSWORD = 'secret';

    private $password;
    
    public function setup(): void
    {
        parent::setUp();
        $this->seed(PronounSeeder::class);
        $this->seed(OccupationSeeder::class);
        $this->seed(RegionSeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CountySeeder::class);
        $this->password = Str::random(12);
        $this->app->setLocale("pt-BR");
        $this->url = '/api/auth';
        // $this->withoutExceptionHandling();
    }

    /** @test */
    public function should_return_status_422_when_recoverpassword_provide_nonexisted_user_email()
    {
        $response = $this->postJson("{$this->url}/recover-password", ["email" => "tester@mail.com"]);

        $response->assertStatus(422);

        $response->assertJson(array('errors' => [
            'Não existe registro'
        ]));     
    }

    /** @test */
    public function should_return_status_422_when_no_provide_user_email()
    {
        $response = $this->postJson("{$this->url}/recover-password", ['email' => '']);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'email' => [
                'O campo email deve ser um endereço de e-mail válido.',
                'O campo email é obrigatório.'
            ]           
        ]);      
    }

    /** @test */
    public function should_return_status_422_when_resetpassword_provide_incorrect_password_pattern()
    {
        $response = $this->postJson("{$this->url}/reset-password", [
            'email' => 'teste@mail.com',
            'password' => $this->password,
            "password_confirmation" => $this->password,
            'token' => Str::random(60)
        ]);

        $response->assertStatus(422);

        $response->assertJson(array('errors' => [
            'A Senha deve ter mínimo de oito caracteres, ao menos uma letra, um número e um caracter especial'
        ]));        
    }

    /** @test */
    public function should_return_status_422_when_resetpassword_provide_non_identical_password_confirmation()
    {
        $response = $this->postJson("{$this->url}/reset-password", [
            'email' => 'teste@mail.com',
            'password' => $this->password,
            "password_confirmation" => 'absd',
            'token' => Str::random(60)
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'password_confirmation' => [
                'Os campos confirmação da senha e senha devem corresponder.',              
            ]           
        ]);    
    }

    /** @test */
    public function should_return_status_422_when_resetpassword_provide_nonexisted_user_email()
    {
        $response = $this->postJson("{$this->url}/reset-password", [
            'email' => 'nonexisted@mail.com',
            'password' => '#testiculo20',
            "password_confirmation" => '#testiculo20',
            'token' => Str::random(60)
        ]);

        $response->assertStatus(422);

        $response->assertJson(array('errors' => [
            'Não foi possível criar uma nova senha'
        ]));  
    }

    /** @test */
    public function should_not_create_new_password_when_no_provide_correct_inputs()
    {
        $response = $this->postJson("{$this->url}/reset-password", []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'email' => [
                'O campo email é obrigatório.',              
            ],
            'password' => [
                'O campo senha é obrigatório.',              
            ],
            'password_confirmation' => [
                'O campo confirmação da senha é obrigatório.',              
            ],
            'token' => [
                'O campo token é obrigatório.',              
            ]
        ]);   
    }

    /** @test */
    public function should_reset_password()
    {
        
        $profile = Profile::factory()->create(['name' => 'Admin']);
        $user = User::factory()->create([
                        'password' => bcrypt(self::USER_ORIGINAL_PASSWORD),
                    ]);

        $token = Password::broker()->createToken($user);
    
        $password = Str::random(8) . '#20';
    
        $this
            ->followingRedirects()
            ->from(route(self::ROUTE_PASSWORD_RESET, [
                'token' => $token,
            ]))
            ->post(route(self::ROUTE_PASSWORD_RESET_SUBMIT), [
                'token' => $token,
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => $password,
            ])
            ->assertSuccessful();
            
        $user->refresh();
    
        $this->assertFalse(Hash::check(self::USER_ORIGINAL_PASSWORD,
            $user->password));
    
        $this->assertTrue(Hash::check($password, $user->password));
    }
}
