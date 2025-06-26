<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MFLService;
use Illuminate\Support\Facades\Http;

class TestMFLIntegrationCommand extends Command
{
    protected $signature = 'mfl:test-integration';
    protected $description = 'Test the MFL API integration';

    protected $mflService;

    public function __construct(MFLService $mflService)
    {
        parent::__construct();
        $this->mflService = $mflService;
    }

    public function handle()
    {
        $this->info('Testing MFL API Integration...');

        // Test 1: Basic API Connection
        $this->testBasicConnection();

        // Test 2: Fetch Facilities
        $this->testFetchFacilities();

        // Test 3: Test Filters
        $this->testFilters();

        // Test 4: Test Facility Details
        $this->testFacilityDetails();

        $this->info('MFL API Integration Tests Completed!');
    }

    protected function testBasicConnection()
    {
        $this->info("\nTesting Basic API Connection...");

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.mfl.api_key'),
                'Accept' => 'application/json'
            ])->get(config('services.mfl.url') . '/api/facilities/facilities/');

            if ($response->successful()) {
                $this->info('✓ Basic API connection successful');
                $this->info('Response Status: ' . $response->status());
                $this->info('Total Facilities: ' . ($response->json()['count'] ?? 'N/A'));
            } else {
                $this->error('✗ Basic API connection failed');
                $this->error('Status Code: ' . $response->status());
                $this->error('Response: ' . $response->body());
            }
        } catch (\Exception $e) {
            $this->error('✗ Basic API connection failed with exception');
            $this->error('Error: ' . $e->getMessage());
        }
    }

    protected function testFetchFacilities()
    {
        $this->info("\nTesting Facility Fetch...");

        try {
            $result = $this->mflService->sync();
            
            if ($result['success']) {
                $this->info('✓ Facility fetch successful');
                $this->info('Message: ' . $result['message']);
            } else {
                $this->error('✗ Facility fetch failed');
                $this->error('Error: ' . $result['message']);
            }
        } catch (\Exception $e) {
            $this->error('✗ Facility fetch failed with exception');
            $this->error('Error: ' . $e->getMessage());
        }
    }

    protected function testFilters()
    {
        $this->info("\nTesting Facility Filters...");

        $filters = [
            'name' => 'molo',
            'code' => '15003,15002',
            'facility_type' => '11494347-f40c-4fbb-8632-cc1f35fe1fc9'
        ];

        foreach ($filters as $type => $value) {
            $this->info("\nTesting {$type} filter...");
            
            try {
                $result = $this->mflService->sync([$type => $value]);
                
                if ($result['success']) {
                    $this->info("✓ {$type} filter test successful");
                } else {
                    $this->error("✗ {$type} filter test failed");
                    $this->error('Error: ' . $result['message']);
                }
            } catch (\Exception $e) {
                $this->error("✗ {$type} filter test failed with exception");
                $this->error('Error: ' . $e->getMessage());
            }
        }
    }

    protected function testFacilityDetails()
    {
        $this->info("\nTesting Facility Details...");

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.mfl.api_key'),
                'Accept' => 'application/json'
            ])->get(config('services.mfl.url') . '/api/facilities/facilities/2927d31f-b1a0-4d17-93b0-ea648af7b9f0/');

            if ($response->successful()) {
                $facility = $response->json();
                $this->info('✓ Facility details fetch successful');
                $this->info('Facility Name: ' . ($facility['name'] ?? 'N/A'));
                $this->info('Facility Code: ' . ($facility['code'] ?? 'N/A'));
                $this->info('Facility Type: ' . ($facility['facility_type'] ?? 'N/A'));
            } else {
                $this->error('✗ Facility details fetch failed');
                $this->error('Status Code: ' . $response->status());
                $this->error('Response: ' . $response->body());
            }
        } catch (\Exception $e) {
            $this->error('✗ Facility details fetch failed with exception');
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 