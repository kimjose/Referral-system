<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Facility;
use App\Models\CommunityHealthUnit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MflController extends Controller
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.mfl.base_url');
        $this->apiKey = config('services.mfl.api_key');
    }

    /**
     * Fetch facilities from MFL API
     */
    public function getFacilities(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->baseUrl}/facilities", [
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'status' => $request->status,
                'county' => $request->county
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'MFL API Error',
                    'message' => $response->json()['message'] ?? 'Failed to fetch facilities'
                ], $response->status());
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('MFL API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to connect to MFL API'
            ], 500);
        }
    }

    /**
     * Fetch a single facility from MFL API
     */
    public function getFacility($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->baseUrl}/facilities/{$id}");

            if ($response->failed()) {
                return response()->json([
                    'error' => 'MFL API Error',
                    'message' => $response->json()['message'] ?? 'Failed to fetch facility'
                ], $response->status());
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('MFL API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to connect to MFL API'
            ], 500);
        }
    }

    /**
     * Fetch community health units from MFL API
     */
    public function getCommunityHealthUnits(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->baseUrl}/community-health-units", [
                'facility_id' => $request->facility_id,
                'status' => $request->status,
                'county' => $request->county
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'MFL API Error',
                    'message' => $response->json()['message'] ?? 'Failed to fetch CHUs'
                ], $response->status());
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('MFL API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to connect to MFL API'
            ], 500);
        }
    }

    /**
     * Sync facilities with local database
     */
    public function syncFacilities()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->baseUrl}/facilities");

            if ($response->failed()) {
                return response()->json([
                    'error' => 'MFL API Error',
                    'message' => $response->json()['message'] ?? 'Failed to sync facilities'
                ], $response->status());
            }

            $facilities = $response->json()['data'] ?? [];
            $errors = [];

            foreach ($facilities as $facility) {
                $validator = Validator::make($facility, [
                    'name' => 'required|string|max:255',
                    'code' => 'required|string|max:50',
                    'type' => 'required|string|max:100',
                    'status' => 'required|string|in:Active,Inactive',
                    'county' => 'required|string|max:100',
                    'sub_county' => 'nullable|string|max:100',
                    'ward' => 'nullable|string|max:100'
                ]);

                if ($validator->fails()) {
                    $errors[] = [
                        'facility' => $facility,
                        'errors' => $validator->errors()
                    ];
                    continue;
                }

                Facility::updateOrCreate(
                    ['mfl_code' => $facility['code']],
                    [
                        'name' => $facility['name'],
                        'type' => $facility['type'],
                        'status' => $facility['status'],
                        'county' => $facility['county'],
                        'sub_county' => $facility['sub_county'] ?? null,
                        'ward' => $facility['ward'] ?? null
                    ]
                );
            }

            if (!empty($errors)) {
                return response()->json([
                    'message' => 'Facilities synchronized with some errors',
                    'errors' => $errors
                ], 422);
            }

            return response()->json([
                'message' => 'Facilities synchronized successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('MFL Sync Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to sync facilities'
            ], 500);
        }
    }
} 