<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Profile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// class ProfileTest extends TestCase
// {
//     use RefreshDatabase;
//     use WithFaker;

//     public function setup(): void
//     {
//         parent::setUp();

//         // $this->withoutExceptionHandling();
//     }

//     /** @test */
//     public function should_list_profiles()
//     {
//         $response = $this->get('/api/profiles');

//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function should_show_a_single_profile()
//     {
//         $profile = Profile::factory()->create();
//         $id = $profile->id;
//         $name = $profile->name;

//         $response = $this->get("/api/profiles/$id");

//         $profileSaved = Profile::first();

//         $response->assertStatus(200);
        
//         $this->assertEquals($name, $profileSaved->name);
//     }

//     /** @test */
//     public function should_return_status_422_when_consult_nonexisted_profile()
//     {
//         $id = 10;

//         $response = $this->get("/api/profiles/$id");

//         $response->assertStatus(422);
//     }

//     /** @test */
//     public function should_not_register_profile_with_duplicate_name()
//     {
//         $name = $this->faker->name();
       
//         $this->post('/api/profiles', ['name' => $name]);

//         $response = $this->post('/api/profiles', ['name' => $name]);

//         $response->assertStatus(422);
//     }

//     /** @test */
//     public function should_not_register_profile_without_name()
//     {
//         $response = $this->post('/api/profiles', ['name' => '']);

//         $bodyResponse = $response->getContent();
//         $json = json_decode($bodyResponse);
//         $errorMessage = (array)$json->errors;

//         $messageErrorValidation = $this->findValidationMessages('The name field is required.', $errorMessage);

//         $response->assertStatus(422);
//         $this->assertEquals(true, $messageErrorValidation);
//     }

//     /** @test */
//     public function should_create_profiles()
//     {
//         $name = $this->faker->name();
       
//         $response = $this->post('/api/profiles', ['name' => $name]);

//         $response->assertStatus(201);

//         $profile = Profile::first();
       
//         $this->assertEquals(1, Profile::count());
//         $this->assertEquals($name, $profile->name);
//     }

//     /** @test */
//     public function should_update_profiles()
//     {
//         $profile = Profile::factory()->create();
//         $profile->name = 'modified name';
       
//         $response = $this->put("/api/profiles/$profile->id", $profile->toArray());

//         $response->assertStatus(200);

//         $profileSaved = Profile::first();
    
//         $this->assertEquals('modified name', $profileSaved->name);
//     }

//     /** @test */
//     public function should_delete_profiles()
//     {
//         $profile = Profile::factory()->create();
       
//         $response = $this->delete("/api/profiles/$profile->id");

//         $response->assertStatus(200);

//         $this->assertEquals(0, Profile::count());
//     }
// }
