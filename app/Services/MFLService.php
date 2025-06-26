<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Facility;
use App\Models\CommunityHealthUnit;
use App\Models\CommunityHealthWorker;
use App\Models\FacilityContact;
use App\Models\FacilityAddress;
use App\Models\FacilityService;
use App\Models\SyncLog;

class MFLService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.mfl.url');
        $this->apiKey = config('services.mfl.api_key');
    }

    public function sync($filters = [])
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->baseUrl}/facilities", $filters);

            if ($response->failed()) {
                throw new \Exception("MFL API request failed: " . $response->body());
            }

            $data = $response->json();
            $this->processFacilities($data['results']);

            // Handle pagination if present
            while (isset($data['next']) && $data['next']) {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Accept' => 'application/json'
                ])->get($data['next']);

                if ($response->failed()) {
                    throw new \Exception("MFL API pagination request failed: " . $response->body());
                }

                $data = $response->json();
                $this->processFacilities($data['results']);
            }

            return [
                'success' => true,
                'message' => 'Facilities synchronized successfully'
            ];
        } catch (\Exception $e) {
            Log::error('MFL sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            SyncLog::create([
                'type' => 'mfl',
                'status' => 'error',
                'message' => 'Failed to sync with MFL API: ' . $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to sync with MFL API: ' . $e->getMessage()
            ];
        }
    }

    protected function processFacilities($facilities)
    {
        foreach ($facilities as $facility) {
            $this->validateFacility($facility);

            Facility::updateOrCreate(
                ['mfl_code' => $facility['code']],
                [
                    'name' => $facility['name'],
                    'facility_type_id' => $facility['facility_type'],
                    'operation_status' => $facility['operation_status'],
                    'ward_id' => $facility['ward'],
                    'mfl_uuid' => $facility['id'],
                    'last_synced_at' => now()
                ]
            );
        }
    }

    protected function validateFacility($facility)
    {
        $validator = validator($facility, [
            'id' => 'required|uuid',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'facility_type' => 'required|uuid',
            'operation_status' => 'required|string|in:active,inactive',
            'ward' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException(
                'Invalid facility data: ' . implode(', ', $validator->errors()->all())
            );
        }
    }

    /**
     * Get all facilities with their details
     */
    public function syncFacilities()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/api/facilities/');

            if ($response->successful()) {
                $facilities = $response->json()['results'];
                
                foreach ($facilities as $facility) {
                    $this->processFacility($facility);
                }

                Log::info('MFL facilities sync completed successfully');
                return true;
            }

            Log::error('MFL facilities sync failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('MFL facilities sync error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process and store facility data
     */
    protected function processFacility($facility)
    {
        // Create or update facility
        $facilityModel = Facility::updateOrCreate(
            ['mfl_code' => $facility['code']],
            [
                'name' => $facility['name'],
                'facility_type' => $facility['facility_type'],
                'is_active' => $facility['active'] ?? true,
                'registration_number' => $facility['registration_number'] ?? null,
                'keph_level' => $facility['keph_level'] ?? null,
            ]
        );

        // Sync facility contacts
        $this->syncFacilityContacts($facilityModel, $facility['id']);

        // Sync facility addresses
        $this->syncFacilityAddresses($facilityModel, $facility['id']);

        // Sync facility services
        $this->syncFacilityServices($facilityModel, $facility['id']);

        // Sync community health units
        $this->syncCommunityHealthUnits($facilityModel, $facility['id']);
    }

    /**
     * Sync facility contacts
     */
    protected function syncFacilityContacts($facility, $facilityId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/api/facilities/{$facilityId}/contacts/");

            if ($response->successful()) {
                $contacts = $response->json()['results'];
                
                foreach ($contacts as $contact) {
                    FacilityContact::updateOrCreate(
                        [
                            'facility_id' => $facility->id,
                            'contact_type' => $contact['contact_type'],
                        ],
                        [
                            'contact_value' => $contact['contact'],
                            'is_active' => true,
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error("Error syncing contacts for facility {$facility->id}: " . $e->getMessage());
        }
    }

    /**
     * Sync facility addresses
     */
    protected function syncFacilityAddresses($facility, $facilityId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/api/facilities/{$facilityId}/addresses/");

            if ($response->successful()) {
                $addresses = $response->json()['results'];
                
                foreach ($addresses as $address) {
                    FacilityAddress::updateOrCreate(
                        [
                            'facility_id' => $facility->id,
                            'address_type' => $address['address_type'],
                        ],
                        [
                            'address' => $address['address'],
                            'postal_code' => $address['postal_code'] ?? null,
                            'county' => $address['county'] ?? null,
                            'sub_county' => $address['sub_county'] ?? null,
                            'ward' => $address['ward'] ?? null,
                            'is_active' => true,
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error("Error syncing addresses for facility {$facility->id}: " . $e->getMessage());
        }
    }

    /**
     * Sync facility services
     */
    protected function syncFacilityServices($facility, $facilityId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/api/facilities/{$facilityId}/services/");

            if ($response->successful()) {
                $services = $response->json()['results'];
                
                foreach ($services as $service) {
                    FacilityService::updateOrCreate(
                        [
                            'facility_id' => $facility->id,
                            'service_id' => $service['service_id'],
                        ],
                        [
                            'service_name' => $service['service_name'],
                            'is_active' => $service['active'] ?? true,
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error("Error syncing services for facility {$facility->id}: " . $e->getMessage());
        }
    }

    /**
     * Sync community health units
     */
    protected function syncCommunityHealthUnits($facility, $facilityId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/api/chul/units/?facility={$facilityId}");

            if ($response->successful()) {
                $units = $response->json()['results'];
                
                foreach ($units as $unit) {
                    $healthUnit = CommunityHealthUnit::updateOrCreate(
                        [
                            'facility_id' => $facility->id,
                            'mfl_code' => $unit['code'],
                        ],
                        [
                            'name' => $unit['name'],
                            'status' => $unit['status'],
                            'is_active' => $unit['active'] ?? true,
                        ]
                    );

                    // Sync community health workers for this unit
                    $this->syncCommunityHealthWorkers($healthUnit, $unit['id']);
                }
            }
        } catch (\Exception $e) {
            Log::error("Error syncing community health units for facility {$facility->id}: " . $e->getMessage());
        }
    }

    /**
     * Sync community health workers
     */
    protected function syncCommunityHealthWorkers($healthUnit, $unitId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/api/chul/workers/?health_unit={$unitId}");

            if ($response->successful()) {
                $workers = $response->json()['results'];
                
                foreach ($workers as $worker) {
                    CommunityHealthWorker::updateOrCreate(
                        [
                            'health_unit_id' => $healthUnit->id,
                            'id_number' => $worker['id_number'],
                        ],
                        [
                            'first_name' => $worker['first_name'],
                            'last_name' => $worker['last_name'],
                            'surname' => $worker['surname'],
                            'is_active' => $worker['active'] ?? true,
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error("Error syncing community health workers for unit {$healthUnit->id}: " . $e->getMessage());
        }
    }
} 