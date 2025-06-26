<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;

class FhirService
{
    /**
     * Convert a referral to FHIR ServiceRequest format
     */
    public function referralToServiceRequest(Referral $referral)
    {
        return [
            'resourceType' => 'ServiceRequest',
            'id' => $referral->id,
            'status' => $this->mapStatus($referral->status),
            'intent' => 'order',
            'category' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => '3457005',
                            'display' => 'Patient referral'
                        ]
                    ]
                ]
            ],
            'priority' => $this->mapPriority($referral->priorityLevel),
            'code' => [
                'coding' => [
                    [
                        'system' => 'http://snomed.info/sct',
                        'code' => $referral->diagnosis,
                        'display' => $referral->kmhflConcepts->{'from concept name'} ?? ''
                    ]
                ]
            ],
            'subject' => [
                'reference' => "Patient/{$referral->clientUPI}",
                'type' => 'Patient'
            ],
            'encounter' => [
                'reference' => "Encounter/{$referral->id}",
                'type' => 'Encounter'
            ],
            'authoredOn' => $referral->created_at,
            'requester' => [
                'reference' => "Practitioner/{$referral->referringOfficer}",
                'type' => 'Practitioner'
            ],
            'performer' => [
                [
                    'reference' => "Organization/{$referral->referredFacility}",
                    'type' => 'Organization'
                ]
            ],
            'reasonCode' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => $referral->reasonReferral,
                            'display' => $referral->additionalNotes
                        ]
                    ]
                ]
            ],
            'supportingInfo' => [
                [
                    'reference' => "DocumentReference/{$referral->id}",
                    'type' => 'DocumentReference',
                    'display' => $referral->historyInvestigation
                ]
            ],
            'note' => [
                [
                    'text' => $referral->serviceNotes
                ]
            ]
        ];
    }

    /**
     * Validate FHIR ServiceRequest
     */
    public function validateServiceRequest(array $data)
    {
        return Validator::make($data, [
            'resourceType' => 'required|in:ServiceRequest',
            'status' => 'required|in:draft,active,on-hold,revoked,completed,entered-in-error,unknown',
            'intent' => 'required|in:proposal,plan,order,original-order,reflex-order,filler-order,instance-order,option',
            'subject' => 'required|array',
            'subject.reference' => 'required|string',
            'subject.type' => 'required|in:Patient',
            'requester' => 'required|array',
            'requester.reference' => 'required|string',
            'requester.type' => 'required|in:Practitioner,Organization,Patient,RelatedPerson,Device',
            'performer' => 'required|array',
            'performer.*.reference' => 'required|string',
            'performer.*.type' => 'required|in:HealthcareService,Organization,Practitioner,PractitionerRole,Device,Patient,RelatedPerson',
            'authoredOn' => 'required|date',
            'reasonCode' => 'array',
            'reasonCode.*.coding' => 'array',
            'reasonCode.*.coding.*.system' => 'required|string',
            'reasonCode.*.coding.*.code' => 'required|string',
            'reasonCode.*.coding.*.display' => 'required|string'
        ]);
    }

    /**
     * Map internal status to FHIR status
     */
    private function mapStatus($status)
    {
        $statusMap = [
            'Pending' => 'active',
            'Accepted' => 'completed',
            'Rejected' => 'revoked'
        ];

        return $statusMap[$status] ?? 'unknown';
    }

    /**
     * Map internal priority to FHIR priority
     */
    private function mapPriority($priority)
    {
        $priorityMap = [
            'urgent' => 'stat',
            'less urgent' => 'routine',
            'asap' => 'urgent',
            'mild' => 'routine'
        ];

        return $priorityMap[$priority] ?? 'routine';
    }

    /**
     * Create OperationOutcome for errors
     */
    public function createOperationOutcome($severity, $code, $message)
    {
        return [
            'resourceType' => 'OperationOutcome',
            'issue' => [
                [
                    'severity' => $severity,
                    'code' => $code,
                    'details' => [
                        'text' => $message
                    ]
                ]
            ]
        ];
    }
} 