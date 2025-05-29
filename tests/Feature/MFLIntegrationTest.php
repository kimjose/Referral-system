<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use App\Models\Facility;
use Illuminate\Support\Facades\Config;

class MflIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $baseUrl;
    protected $apiKey;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set test configuration
        Config::set('services.mfl.base_url', 'https://api.mfl.health.go.ke/api/v1');
        Config::set('services.mfl.api_key', 'test_api_key');
        
        $this->baseUrl = config('services.mfl.base_url');
        $this->apiKey = config('services.mfl.api_key');
    }

    /** @test */
    public function it_can_fetch_facilities_from_mfl()
    {
        Http::fake([
            "{$this->baseUrl}/facilities*" => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Test Facility',
                        'code' => 'MFL001',
                        'type' => 'Hospital',
                        'status' => 'Active',
                        'county' => 'Nairobi',
                        'sub_county' => 'Westlands',
                        'ward' => 'Parklands'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/mfl/facilities');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'code',
                        'type',
                        'status',
                        'county',
                        'sub_county',
                        'ward'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_fetch_single_facility_from_mfl()
    {
        $facilityId = 1;

        Http::fake([
            "{$this->baseUrl}/facilities/{$facilityId}*" => Http::response([
                'data' => [
                    'id' => $facilityId,
                    'name' => 'Test Facility',
                    'code' => 'MFL001',
                    'type' => 'Hospital',
                    'status' => 'Active',
                    'county' => 'Nairobi',
                    'sub_county' => 'Westlands',
                    'ward' => 'Parklands'
                ]
            ], 200)
        ]);

        $response = $this->getJson("/api/mfl/facilities/{$facilityId}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'code',
                    'type',
                    'status',
                    'county',
                    'sub_county',
                    'ward'
                ]
            ]);
    }

    /** @test */
    public function it_can_fetch_community_health_units()
    {
        Http::fake([
            "{$this->baseUrl}/community-health-units*" => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Test CHU',
                        'code' => 'CHU001',
                        'facility_id' => 1,
                        'status' => 'Active',
                        'county' => 'Nairobi',
                        'sub_county' => 'Westlands',
                        'ward' => 'Parklands'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/mfl/community-health-units');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'code',
                        'facility_id',
                        'status',
                        'county',
                        'sub_county',
                        'ward'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_handles_mfl_api_errors_gracefully()
    {
        Http::fake([
            "{$this->baseUrl}/facilities*" => Http::response([
                'error' => 'API Error'
            ], 500)
        ]);

        $response = $this->getJson('/api/mfl/facilities');

        $response->assertStatus(500)
            ->assertJsonStructure([
                'error',
                'message'
            ]);
    }

    /** @test */
    public function it_validates_facility_data_before_saving()
    {
        $invalidFacility = [
            'name' => '', // Invalid empty name
            'code' => 'MFL001',
            'type' => 'Hospital',
            'status' => 'Active'
        ];

        $response = $this->postJson('/api/mfl/facilities', $invalidFacility);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_can_sync_facility_data_with_local_database()
    {
        Http::fake([
            "{$this->baseUrl}/facilities*" => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Test Facility',
                        'code' => 'MFL001',
                        'type' => 'Hospital',
                        'status' => 'Active',
                        'county' => 'Nairobi',
                        'sub_county' => 'Westlands',
                        'ward' => 'Parklands'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/mfl/sync');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Facilities synchronized successfully'
            ]);

        $this->assertDatabaseHas('facilities', [
            'mfl_code' => 'MFL001',
            'name' => 'Test Facility'
        ]);
    }

    /** @test */
    public function it_validates_required_fields_for_facility_sync()
    {
        Http::fake([
            "{$this->baseUrl}/facilities*" => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'name' => '', // Missing required name
                        'code' => '', // Missing required code
                        'type' => 'Hospital',
                        'status' => 'Active'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/mfl/sync');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'code']);
    }
} 