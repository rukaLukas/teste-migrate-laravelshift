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

class GroupTest extends TestCase
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
        $this->url = '/api/groups';

        $this->userAuth = Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );

        $this->withoutExceptionHandling();
    }

    /** @test */
    // TODO ao cadastrar regional criar por padrão subregional e salas de vacinas para o município com base
    // na tabela cnes_establecimento
}
