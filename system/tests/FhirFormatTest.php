<?php

namespace Tests;

use Tests\TestCase;
use App\Services\FhirService;
use App\Models\Referral;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FhirFormatTest extends TestCase
{
    use RefreshDatabase;

    private FhirService $fhirService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fhirService = new FhirService();
    }

    public function testReferralToServiceRequestConversion()
    {
        // Create a mock Referral object
        $referral = new Referral();
        $referral->id = 1;
        $referral->clientUPI = 'P12345';
        $referral->referringOfficer = 'DOC123';
        $referral->referredFacility = 'FAC123';
        $referral->status = 'Pending';
        $referral->priorityLevel = 'urgent';
        $referral->diagnosis = '123456';
        $referral->reasonReferral = '789012';
        $referral->additionalNotes = 'Specialist consultation required';
        $referral->historyInvestigation = 'Previous medical history';
        $referral->serviceNotes = 'Patient needs urgent attention';
        $referral->created_at = '2024-03-20';

        // Convert to FHIR format
        $fhirResource = $this->fhirService->referralToServiceRequest($referral);

        // Assert FHIR structure
        $this->assertIsArray($fhirResource);
        $this->assertEquals('ServiceRequest', $fhirResource['resourceType']);
        $this->assertEquals('active', $fhirResource['status']);
        $this->assertEquals('stat', $fhirResource['priority']);
        $this->assertArrayHasKey('subject', $fhirResource);
        $this->assertArrayHasKey('requester', $fhirResource);
        $this->assertArrayHasKey('performer', $fhirResource);
        $this->assertEquals('Patient/P12345', $fhirResource['subject']['reference']);
        $this->assertEquals('Practitioner/DOC123', $fhirResource['requester']['reference']);
        $this->assertEquals('Organization/FAC123', $fhirResource['performer'][0]['reference']);
    }

    public function testValidateServiceRequest()
    {
        // Sample FHIR resource
        $fhirResource = [
            'resourceType' => 'ServiceRequest',
            'status' => 'active',
            'intent' => 'order',
            'subject' => [
                'reference' => 'Patient/P12345',
                'type' => 'Patient'
            ],
            'requester' => [
                'reference' => 'Practitioner/DOC123',
                'type' => 'Practitioner'
            ],
            'performer' => [
                [
                    'reference' => 'Organization/FAC123',
                    'type' => 'Organization'
                ]
            ],
            'authoredOn' => '2024-03-20',
            'reasonCode' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => '789012',
                            'display' => 'Specialist consultation required'
                        ]
                    ]
                ]
            ]
        ];

        // Validate FHIR resource
        $validator = $this->fhirService->validateServiceRequest($fhirResource);

        // Assert validation passes
        $this->assertTrue($validator->passes());
    }
} 