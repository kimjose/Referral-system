<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\MFLService;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MFLIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $mflBaseUrl = 'https://api.mfl.health.go.ke/api/facilities';

    public function test_can_fetch_facilities()
    {
        Http::fake([
            "{$this->mflBaseUrl}/facilities/*" => Http::response([
                'id' => '2927d31f-b1a0-4d17-93b0-ea648af7b9f0',
                'name' => 'Test Hospital',
                'code' => '15003',
                'facility_type' => '11494347-f40c-4fbb-8632-cc1f35fe1fc9',
                'operation_status' => 'active',
                'ward' => '353404d7-02e6-422f-b64f-b1c7d0f1bcf0'
            ], 200)
        ]);

        $response = $this->getJson('/api/facilities/sync');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Facilities synchronized successfully'
            ]);

        $this->assertDatabaseHas('facilities', [
            'mfl_code' => '15003',
            'name' => 'Test Hospital'
        ]);
    }

    public function test_can_filter_facilities_by_name()
    {
        Http::fake([
            "{$this->mflBaseUrl}/facilities/?name=molo*" => Http::response([
                'count' => 1,
                'results' => [
                    [
                        'id' => '2927d31f-b1a0-4d17-93b0-ea648af7b9f0',
                        'name' => 'Molo District Hospital',
                        'code' => '15004'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/facilities/sync?name=molo');

        $response->assertStatus(200);
        $this->assertDatabaseHas('facilities', [
            'name' => 'Molo District Hospital',
            'mfl_code' => '15004'
        ]);
    }

    public function test_can_filter_facilities_by_code()
    {
        Http::fake([
            "{$this->mflBaseUrl}/facilities/?code=15003,15002*" => Http::response([
                'count' => 2,
                'results' => [
                    [
                        'id' => '2927d31f-b1a0-4d17-93b0-ea648af7b9f0',
                        'name' => 'Hospital 1',
                        'code' => '15003'
                    ],
                    [
                        'id' => '2927d31f-b1a0-4d17-93b0-ea648af7b9f1',
                        'name' => 'Hospital 2',
                        'code' => '15002'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/facilities/sync?code=15003,15002');

        $response->assertStatus(200);
        $this->assertDatabaseHas('facilities', [
            'mfl_code' => '15003',
            'name' => 'Hospital 1'
        ]);
        $this->assertDatabaseHas('facilities', [
            'mfl_code' => '15002',
            'name' => 'Hospital 2'
        ]);
    }

    public function test_can_filter_facilities_by_type()
    {
        Http::fake([
            "{$this->mflBaseUrl}/facilities/?facility_type=11494347-f40c-4fbb-8632-cc1f35fe1fc9*" => Http::response([
                'count' => 1,
                'results' => [
                    [
                        'id' => '2927d31f-b1a0-4d17-93b0-ea648af7b9f0',
                        'name' => 'District Hospital',
                        'code' => '15003',
                        'facility_type' => '11494347-f40c-4fbb-8632-cc1f35fe1fc9'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/facilities/sync?facility_type=11494347-f40c-4fbb-8632-cc1f35fe1fc9');

        $response->assertStatus(200);
        $this->assertDatabaseHas('facilities', [
            'mfl_code' => '15003',
            'name' => 'District Hospital'
        ]);
    }

    public function test_handles_api_errors()
    {
        Http::fake([
            "{$this->mflBaseUrl}/facilities/*" => Http::response([
                'error' => 'Internal Server Error'
            ], 500)
        ]);

        $response = $this->getJson('/api/facilities/sync');

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => 'Failed to sync with MFL API'
            ]);

        $this->assertDatabaseHas('sync_logs', [
            'type' => 'mfl',
            'status' => 'error',
            'message' => 'Failed to sync with MFL API'
        ]);
    }

    public function test_handles_api_timeout()
    {
        Http::fake([
            "{$this->mflBaseUrl}/facilities/*" => Http::timeout()
        ]);

        $response = $this->getJson('/api/facilities/sync');

        $response->assertStatus(504)
            ->assertJson([
                'success' => false,
                'message' => 'MFL API request timed out'
            ]);
    }

    public function test_validates_facility_data()
    {
        Http::fake([
            "{$this->mflBaseUrl}/facilities/*" => Http::response([
                'id' => '2927d31f-b1a0-4d17-93b0-ea648af7b9f0',
                'name' => '', // Invalid empty name
                'code' => null, // Invalid null code
                'facility_type' => 'invalid-uuid' // Invalid UUID
            ], 200)
        ]);

        $response = $this->getJson('/api/facilities/sync');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'code', 'facility_type']);
    }

    public function test_handles_pagination()
    {
        Http::fake([
            "{$this->mflBaseUrl}/facilities/*" => Http::sequence()
                ->push([
                    'count' => 100,
                    'next' => "{$this->mflBaseUrl}/facilities/?page=2",
                    'results' => array_fill(0, 50, [
                        'id' => '2927d31f-b1a0-4d17-93b0-ea648af7b9f0',
                        'name' => 'Hospital 1',
                        'code' => '15003'
                    ])
                ], 200)
                ->push([
                    'count' => 100,
                    'next' => null,
                    'results' => array_fill(0, 50, [
                        'id' => '2927d31f-b1a0-4d17-93b0-ea648af7b9f1',
                        'name' => 'Hospital 2',
                        'code' => '15004'
                    ])
                ], 200)
        ]);

        $response = $this->getJson('/api/facilities/sync');

        $response->assertStatus(200);
        $this->assertDatabaseCount('facilities', 100);
    }
} 