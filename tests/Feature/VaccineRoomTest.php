<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Alert;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Profile;
use App\Models\SubGroup;
use App\Models\SubGroupUser;
use Laravel\Sanctum\Sanctum;
use App\Models\UnderSubGroup;
use Database\Seeders\UserSeeder;
use App\Models\UnderSubGroupUser;
use Database\Seeders\BreedSeeder;
use Database\Seeders\GenreSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Database\Seeders\PronounSeeder;
use Database\Seeders\VaccineSeeder;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\AccessionSeeder;
use Database\Seeders\OccupationSeeder;
use Database\Seeders\StatusAlertSeeder;
use Database\Seeders\TargetPublicSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\ReasonDelayVaccineSeeder;
use Database\Seeders\TypeStatusVaccinationSeeder;
use Database\Seeders\TypeReasonDelayVaccineSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpParser\Node\Stmt\GroupUse;

class VaccineRoomTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setup(): void
    {
        parent::setUp(); 
        Profile::factory()->count(3)->create();        
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
        $this->seed(AccessionSeeder::class);
        Alert::factory()->create();   
        $this->url = '/api/under-sub-groups';        
    }

    /** @test */
    public function should_list_vaccine_rooms_where_user_is_linked_to()
    {
        $this->baseData();
        $countyId = Auth::user()->county_id;
        UnderSubGroupUser::factory()->create([
            'user_id' => Auth::user()->id,
            'under_sub_group_id' => UnderSubGroup::inRandomOrder()->first()->id,
        ]);
        
        $response = $this->get("$this->url?county_id=$countyId");

        $response->assertStatus(200)
            ->assertStatus(200)
            ->assertJsonCount(1, 'data.data');        
    }

    /** @test */
    public function should_list_vaccine_rooms_where_subregion_user_is_linked_to()
    {
        $this->baseData();
        $countyId = Auth::user()->county_id;
        SubGroupUser::factory()->create([
            'user_id' => Auth::user()->id,
            'sub_group_id' => SubGroup::inRandomOrder()->first()->id,
        ]);
        $toalUnderSubGroups = UnderSubGroup::where('sub_group_id', '=', SubGroupUser::first()->sub_group_id)->count();
        
        $this->getJson("$this->url?county_id=$countyId")
            ->assertStatus(200)
            ->assertJsonCount($toalUnderSubGroups, 'data.data');
    }

    /** @test */
    public function should_list_vaccine_rooms_where_region_user_is_linked_to()
    {
        $this->baseData();
        $countyId = Auth::user()->county_id;
        GroupUser::factory()->create([
            'user_id' => Auth::user()->id,
            'group_id' => Group::inRandomOrder()->first()->id,
        ]);

        $idsSubGroups = [];
        GroupUser::first()->group->subGroups->each(function($subGroup) use (&$idsSubGroups) {
            $idsSubGroups[] = $subGroup->id;
        });       
        $totalUnderSubGroups = UnderSubGroup::whereIn('sub_group_id', $idsSubGroups)->count();
        
        $this->getJson("$this->url?county_id=$countyId")
            ->assertStatus(200)
            ->assertJsonCount($totalUnderSubGroups, 'data.data');
    }

    /** @test */
    public function should_list_vaccine_rooms_where_county_user_is_linked_to()
    {
        $this->baseData();
        $countyId = Auth::user()->county_id;
        // get how many under_sub_groups exists in county = $countyId
        Group::where('county_id', $countyId)->each(function($group) use (&$idsSubGroups) {
            $group->subGroups->each(function($subGroup) use (&$idsSubGroups) {
                $idsSubGroups[] = $subGroup->id;
            });
        });
        
        $totalUnderSubGroups = UnderSubGroup::whereIn('sub_group_id', $idsSubGroups)->count();
        
        $this->getJson("$this->url?county_id=$countyId")
            ->assertStatus(200)
            ->assertJsonCount($totalUnderSubGroups, 'data.data');
    }

    private function baseData()
    {
        $accession = $this->accessionJourney();            
        Sanctum::actingAs(
            $accession['userGestorPolitico'],
            ['*']
        );
    }   
}
