<?php

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="Angaza Referral System FHIR API",
 *     version="1.0.0",
 *     description="FHIR-compliant API for patient referrals, patients, and referral bundles. Secured by API key."
 *   )
 * )
 */

namespace App\Http\Controllers;
use App\Models\m_f_l_s;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Notifications\ReferralRequestSent;
use App\utils\SendReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use App\Models\Patient;
use App\Models\Referral;
use App\Models\Mappings;
use App\Services\FhirService;

class ReferralController extends Controller
{
    protected $fhirService;

    public function __construct(FhirService $fhirService)
    {
        $this->fhirService = $fhirService;
    }

    /**
     * @OA\Get(
     *     path="/api/fhir/referral/{referralId}",
     *     summary="Get FHIR ServiceRequest for a referral",
     *     tags={"FHIR"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="referralId",
     *         in="path",
     *         required=true,
     *         description="Referral ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FHIR ServiceRequest resource",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=404, description="Referral not found")
     * )
     */
    public function apiFhirReferral($referralId)
    {
        $referral = \App\Models\Referral::find($referralId);
        if (!$referral) {
            return response()->json(['error' => 'Referral not found'], 404);
        }
        $fhir = $this->fhirService->referralToServiceRequest($referral);
        return response()->json($fhir);
    }

    /**
     * @OA\Get(
     *     path="/api/fhir/patient/{patientId}",
     *     summary="Get FHIR Patient resource",
     *     tags={"FHIR"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="patientId",
     *         in="path",
     *         required=true,
     *         description="Patient ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FHIR Patient resource",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */
    public function apiFhirPatient($patientId)
    {
        $patient = \App\Models\Patient::find($patientId);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        $fhir = [
            'resourceType' => 'Patient',
            'id' => $patient->id,
            'identifier' => [
                [
                    'system' => 'http://health.go.ke/upi',
                    'value' => $patient->upi
                ]
            ],
            'name' => [[
                'family' => $patient->last_name,
                'given' => [$patient->first_name]
            ]],
            'gender' => $patient->gender,
            'birthDate' => $patient->date_of_birth,
            'address' => [[
                'text' => trim("{$patient->village}, {$patient->subCounty}, {$patient->county}, {$patient->country}", ', ')
            ]],
            'telecom' => [
                [
                    'system' => 'phone',
                    'value' => $patient->telephone ?? $patient->phone ?? null
                ]
            ],
        ];
        return response()->json($fhir);
    }

    /**
     * @OA\Get(
     *     path="/api/fhir/patient/{patientId}/referral-bundle",
     *     summary="Get FHIR Bundle for a patient's full referral chain",
     *     tags={"FHIR"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="patientId",
     *         in="path",
     *         required=true,
     *         description="Patient ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FHIR Bundle resource",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */
    public function apiFhirReferralBundle($patientId)
    {
        $patient = \App\Models\Patient::find($patientId);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        $referrals = \App\Models\Referral::where('clientUPI', $patient->upi)->orderBy('created_at')->get();
        $bundle = [
            'resourceType' => 'Bundle',
            'type' => 'collection',
            'entry' => []
        ];
        // Add Patient resource
        $bundle['entry'][] = [
            'resource' => [
                'resourceType' => 'Patient',
                'id' => $patient->id,
                'identifier' => [[
                    'system' => 'http://health.go.ke/upi',
                    'value' => $patient->upi
                ]],
                'name' => [[
                    'family' => $patient->last_name,
                    'given' => [$patient->first_name]
                ]],
                'gender' => $patient->gender,
                'birthDate' => $patient->date_of_birth,
                'address' => [[
                    'text' => trim("{$patient->village}, {$patient->subCounty}, {$patient->county}, {$patient->country}", ', ')
                ]],
                'telecom' => [[
                    'system' => 'phone',
                    'value' => $patient->telephone ?? $patient->phone ?? null
                ]],
            ]
        ];
        // Add all referrals as ServiceRequest resources
        foreach ($referrals as $referral) {
            $bundle['entry'][] = [
                'resource' => $this->fhirService->referralToServiceRequest($referral)
            ];
        }
        return response()->json($bundle);
    }
}
