<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Referral;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class FhirFormatTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_legacy_fhir_format()
    {
        $response = $this->get('/fhirJson?legacy=true');
        
        $response->assertStatus(200)
            ->assertJson([
                'resourceType' => 'referralRequest',
                'status' => 'active',
                'subject' => [
                    'reference' => 'https://client.registry/0-TGGA-rrTT',
                    'code' => '0-TGGA-rrTT',
                    'display' => 'John Doe'
                ],
                'priority' => 'STAT'
            ]);
    }

    public function test_new_fhir_format()
    {
        // Create test data
        $patient = Patient::factory()->create([
            'upi' => 'TEST-UPI-123',
            'first_name' => 'Test',
            'last_name' => 'Patient'
        ]);

        $referral = Referral::factory()->create([
            'clientUPI' => $patient->upi,
            'status' => 'Pending',
            'priorityLevel' => 'urgent',
            'diagnosis' => '123456',
            'referringOfficer' => 'DR-123',
            'referredFacility' => 'FAC-123',
            'reasonReferral' => 'Test reason',
            'additionalNotes' => 'Test notes',
            'historyInvestigation' => 'Test history',
            'serviceNotes' => 'Test service notes'
        ]);

        $response = $this->get('/fhirJson');
        
        $response->assertStatus(200)
            ->assertJson([
                'resourceType' => 'ServiceRequest',
                'status' => 'active',
                'intent' => 'order',
                'subject' => [
                    'reference' => "Patient/{$patient->upi}",
                    'type' => 'Patient'
                ]
            ]);
    }

    public function test_fhir_validation()
    {
        $validData = [
            'resourceType' => 'ServiceRequest',
            'status' => 'active',
            'intent' => 'order',
            'subject' => [
                'reference' => 'Patient/123',
                'type' => 'Patient'
            ],
            'requester' => [
                'reference' => 'Practitioner/456',
                'type' => 'Practitioner'
            ],
            'performer' => [
                [
                    'reference' => 'Organization/789',
                    'type' => 'Organization'
                ]
            ],
            'authoredOn' => now()->toIso8601String()
        ];

        $response = $this->postJson('/fhir/ServiceRequest/$validate', $validData);
        
        $response->assertStatus(200)
            ->assertJson([
                'resourceType' => 'OperationOutcome',
                'issue' => [
                    [
                        'severity' => 'information',
                        'code' => 'informational'
                    ]
                ]
            ]);
    }

    public function test_fhir_submission()
    {
        $validData = [
            'resourceType' => 'ServiceRequest',
            'status' => 'active',
            'intent' => 'order',
            'subject' => [
                'reference' => 'Patient/123',
                'type' => 'Patient'
            ],
            'requester' => [
                'reference' => 'Practitioner/456',
                'type' => 'Practitioner'
            ],
            'performer' => [
                [
                    'reference' => 'Organization/789',
                    'type' => 'Organization'
                ]
            ],
            'authoredOn' => now()->toIso8601String()
        ];

        $response = $this->postJson('/fhir/ServiceRequest', $validData);
        
        $response->assertStatus(201)
            ->assertJson([
                'resourceType' => 'Bundle',
                'type' => 'transaction-response'
            ]);
    }
} 