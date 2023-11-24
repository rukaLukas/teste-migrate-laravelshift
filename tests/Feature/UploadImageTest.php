<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Aws\S3\S3Client;
use App\Models\Profile;
use App\Models\Occupation;
use Laravel\Sanctum\Sanctum;
use Tests\RefreshTestDatabase;
use App\Models\GovernmentAgency;
use Database\Seeders\StateSeeder;
use Illuminate\Http\UploadedFile;
use Database\Seeders\CountySeeder;
use Database\Seeders\RegionSeeder;
use Database\Seeders\PronounSeeder;
use Database\Seeders\OccupationSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UploadImageTest extends TestCase
{
    use RefreshTestDatabase;
    use WithFaker;

    protected $s3Client;

    public function setup(): void
    {
        parent::setUp();

        Profile::factory()->create();
        $this->seed(PronounSeeder::class);
        $this->seed(OccupationSeeder::class);
        $this->seed(RegionSeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CountySeeder::class);
        Sanctum::actingAs(
            User::factory()->create(['occupation_id' => Occupation::GESTOR_NACIONAL]),
            ['*']
        );

        $this->url = '/api/upload-image';
        // $this->withoutExceptionHandling();

        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'endpoint' => env('SPACES_ENDPOINT'),
            'use_path_style_endpoint' => false, 
            'credentials' => [
                'key'    => env('SPACES_KEY'),
                'secret' => env('SPACES_SECRET'),
            ],
        ]);
    }

    /** @test */
    public function avatar_upload()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->post($this->url, [
            'image' => $file,
        ]);

        $response->assertStatus(200);

        $nameUploadedFile = explode('/', $response->json('url'))[3];     
        $this->s3Client->deleteObject([
            'Bucket' =>  env('SPACES_BUCKET'),
            'Key'    => $nameUploadedFile
        ]);        
    }

    /** @test */
    public function should_upload_file()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post($this->url, [
            'image' => $file,
        ]);

        $response->assertStatus(200);

        $nameUploadedFile = explode('/', $response->json('url'))[3];     
        $this->s3Client->deleteObject([
            'Bucket' => env('SPACES_BUCKET'),
            'Key'    => $nameUploadedFile
        ]);
    }

    /** @test */
    public function should_return_status_422_when_not_provide_a_image_file()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('sheets.json');

        $response = $this->json('POST', $this->url, [
            'image' => $file
        ]);

        $response->assertStatus(422);

        $response->assertJson(array('errors' => [
            'image' => [
                'O campo image deve ser um arquivo do tipo: jpg, jpeg, png.'
            ]
        ]));
    }

    /** @test */
    public function should_return_status_422_when_provide_a_image_file_greather_than_5000kb()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg')->size(5200);

        $response = $this->json('POST', $this->url, [
            'image' => $file
        ]);

        $response->assertStatus(422);

        $response->assertJson(array('errors' => [
            'image' => [
                'O tamanho da imagem não pode ser superior a 5MB'
            ]
        ]));
    }
}
