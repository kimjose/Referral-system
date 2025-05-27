<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MFLService
{
    protected $baseUrl;
    protected $apiKey;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = env('MFL_API_URL', 'https://api.kmhfl.health.go.ke/api');
        $this->apiKey = env('MFL_API_KEY');
        $this->token = $this->getToken();
    }

    /**
     * Get authentication token
     */
    protected function getToken()
    {
        return Cache::remember('mfl_token', 3600, function () {
            $response = Http::post($this->baseUrl . '/auth/token/', [
                'username' => env('MFL_USERNAME'),
                'password' => env('MFL_PASSWORD')
            ]);

            if ($response->successful()) {
                return $response->json()['token'];
            }
            throw new \Exception('Failed to get MFL token');
        });
    }

    /**
     * Get facilities by service
     */
    public function getFacilitiesByService($serviceId, $ownerType = null, $county = null)
    {
        $params = [
            'format' => 'json',
            'service' => $serviceId
        ];

        if ($ownerType) {
            $params['owner_type'] = $ownerType;
        }

        if ($county) {
            $params['county_name'] = $county;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->baseUrl . '/facilities/facility_services', $params);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get facilities by service: ' . $response->body());
    }

    /**
     * Get facility details
     */
    public function getFacilityDetails($facilityId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->baseUrl . '/facilities/facilities', [
            'format' => 'json',
            'id' => $facilityId
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get facility details: ' . $response->body());
    }

    /**
     * Get service categories
     */
    public function getServiceCategories()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->baseUrl . '/service_catalog/categories', [
            'format' => 'json'
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get service categories: ' . $response->body());
    }

    /**
     * Get services by category
     */
    public function getServicesByCategory($categoryId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->baseUrl . '/service_catalog/services', [
            'format' => 'json',
            'category' => $categoryId
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get services by category: ' . $response->body());
    }

    /**
     * Get counties
     */
    public function getCounties()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->baseUrl . '/common/counties', [
            'format' => 'json'
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get counties: ' . $response->body());
    }

    /**
     * Get facility types
     */
    public function getFacilityTypes()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->baseUrl . '/facilities/facility_types', [
            'format' => 'json'
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get facility types: ' . $response->body());
    }
} 