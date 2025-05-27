<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Facility;
use App\Models\Referral;
use App\Models\Patient;
use App\Models\HealthRecord;

class IntegrationService
{
    protected $mflService;
    protected $echisBaseUrl;
    protected $shrBaseUrl;
    protected $hieBaseUrl;
    protected $echisApiKey;
    protected $shrApiKey;
    protected $hieApiKey;

    public function __construct(MFLService $mflService)
    {
        $this->mflService = $mflService;
        $this->echisBaseUrl = config('services.echis.base_url');
        $this->shrBaseUrl = config('services.shr.base_url');
        $this->hieBaseUrl = config('services.hie.base_url');
        $this->echisApiKey = config('services.echis.api_key');
        $this->shrApiKey = config('services.shr.api_key');
        $this->hieApiKey = config('services.hie.api_key');
    }

    /**
     * Sync facilities with MFL
     */
    public function syncMFLFacilities()
    {
        return $this->mflService->syncFacilities();
    }

    /**
     * Sync referrals with eCHIS
     */
    public function syncECHISReferrals()
    {
        try {
            // Get referrals that need to be synced
            $referrals = Referral::where('is_synced', false)
                               ->where('status', 'completed')
                               ->get();

            foreach ($referrals as $referral) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->echisApiKey,
                ])->post($this->echisBaseUrl . '/referrals', [
                    'patient_name' => $referral->patient_name,
                    'patient_phone' => $referral->patient_phone,
                    'from_facility' => $referral->fromFacility->mfl_code,
                    'to_facility' => $referral->toFacility->mfl_code,
                    'status' => $referral->status,
                    'notes' => $referral->notes,
                    'created_at' => $referral->created_at
                ]);

                if ($response->successful()) {
                    $referral->update(['is_synced' => true]);
                    Log::info('Referral synced with eCHIS: ' . $referral->id);
                } else {
                    Log::error('Failed to sync referral with eCHIS: ' . $referral->id);
                }
            }

            Log::info('eCHIS referrals sync completed');
            return true;
        } catch (\Exception $e) {
            Log::error('eCHIS sync error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Sync health records with SHR
     */
    public function syncSHRRecords()
    {
        try {
            $patients = Patient::where('is_synced_shr', false)->get();

            foreach ($patients as $patient) {
                $healthRecords = HealthRecord::where('patient_id', $patient->id)->get();
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->shrApiKey,
                ])->post($this->shrBaseUrl . '/health-records', [
                    'patient_id' => $patient->id,
                    'mfl_code' => $patient->facility->mfl_code,
                    'records' => $healthRecords->map(function ($record) {
                        return [
                            'type' => $record->type,
                            'data' => $record->data,
                            'created_at' => $record->created_at,
                            'updated_at' => $record->updated_at
                        ];
                    })->toArray()
                ]);

                if ($response->successful()) {
                    $patient->update(['is_synced_shr' => true]);
                    Log::info('Patient health records synced with SHR: ' . $patient->id);
                } else {
                    Log::error('Failed to sync patient health records with SHR: ' . $patient->id);
                }
            }

            Log::info('SHR health records sync completed');
            return true;
        } catch (\Exception $e) {
            Log::error('SHR sync error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Sync with HIE
     */
    public function syncHIERecords()
    {
        try {
            // Get all unsynced health records
            $records = HealthRecord::where('is_synced_hie', false)->get();

            foreach ($records as $record) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->hieApiKey,
                ])->post($this->hieBaseUrl . '/records', [
                    'patient_id' => $record->patient_id,
                    'facility_id' => $record->facility_id,
                    'record_type' => $record->type,
                    'record_data' => $record->data,
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at,
                    'metadata' => [
                        'source_system' => 'angaza_referral',
                        'version' => '1.0'
                    ]
                ]);

                if ($response->successful()) {
                    $record->update(['is_synced_hie' => true]);
                    Log::info('Health record synced with HIE: ' . $record->id);
                } else {
                    Log::error('Failed to sync health record with HIE: ' . $record->id);
                }
            }

            Log::info('HIE records sync completed');
            return true;
        } catch (\Exception $e) {
            Log::error('HIE sync error: ' . $e->getMessage());
            return false;
        }
    }
} 