# FHIR Compliance

This document outlines the FHIR (Fast Healthcare Interoperability Resources) compliance requirements and implementation details for the Angaza Referral System.

## 1. FHIR Resource Implementation

### 1.1 Core Resources
- **Patient Resource**
  ```json
  {
    "resourceType": "Patient",
    "id": "example-patient",
    "identifier": [
      {
        "system": "http://example.org/fhir/sid/patient",
        "value": "12345"
      }
    ],
    "name": [
      {
        "use": "official",
        "family": "Smith",
        "given": ["John", "James"]
      }
    ],
    "gender": "male",
    "birthDate": "1974-12-25",
    "address": [
      {
        "use": "home",
        "line": ["123 Main St"],
        "city": "Nairobi",
        "country": "KE"
      }
    ]
  }
  ```

- **Practitioner Resource**
  ```json
  {
    "resourceType": "Practitioner",
    "id": "example-practitioner",
    "identifier": [
      {
        "system": "http://example.org/fhir/sid/practitioner",
        "value": "PR12345"
      }
    ],
    "name": [
      {
        "use": "official",
        "family": "Doe",
        "given": ["Jane"]
      }
    ],
    "qualification": [
      {
        "code": {
          "coding": [
            {
              "system": "http://terminology.hl7.org/CodeSystem/v2-0360",
              "code": "MD",
              "display": "Medical Doctor"
            }
          ]
        }
      }
    ]
  }
  ```

### 1.2 Referral Resources
- **ReferralRequest Resource**
  ```json
  {
    "resourceType": "ReferralRequest",
    "id": "example-referral",
    "status": "active",
    "intent": "order",
    "priority": "routine",
    "subject": {
      "reference": "Patient/example-patient"
    },
    "requester": {
      "reference": "Practitioner/example-practitioner"
    },
    "recipient": [
      {
        "reference": "Organization/example-org"
      }
    ],
    "reasonCode": [
      {
        "coding": [
          {
            "system": "http://snomed.info/sct",
            "code": "123456789",
            "display": "Hypertension"
          }
        ]
      }
    ]
  }
  ```

## 2. FHIR API Implementation

### 2.1 RESTful Endpoints
```http
# Patient Endpoints
GET /fhir/Patient
GET /fhir/Patient/{id}
POST /fhir/Patient
PUT /fhir/Patient/{id}
DELETE /fhir/Patient/{id}

# Practitioner Endpoints
GET /fhir/Practitioner
GET /fhir/Practitioner/{id}
POST /fhir/Practitioner
PUT /fhir/Practitioner/{id}
DELETE /fhir/Practitioner/{id}

# Referral Endpoints
GET /fhir/ReferralRequest
GET /fhir/ReferralRequest/{id}
POST /fhir/ReferralRequest
PUT /fhir/ReferralRequest/{id}
DELETE /fhir/ReferralRequest/{id}
```

### 2.2 Search Parameters
```http
# Search by patient identifier
GET /fhir/Patient?identifier=12345

# Search by practitioner specialty
GET /fhir/Practitioner?specialty=cardiology

# Search referrals by status
GET /fhir/ReferralRequest?status=active

# Search referrals by date range
GET /fhir/ReferralRequest?date=ge2024-01-01&date=le2024-12-31
```

## 3. FHIR Compliance Requirements

### 3.1 Version Compliance
```json
{
  "resourceType": "CapabilityStatement",
  "status": "active",
  "fhirVersion": "4.0.1",
  "format": ["application/fhir+json"],
  "rest": [
    {
      "mode": "server",
      "resource": [
        {
          "type": "Patient",
          "interaction": [
            {
              "code": "read"
            },
            {
              "code": "search-type"
            }
          ]
        }
      ]
    }
  ]
}
```

### 3.2 Security Requirements
```http
# OAuth 2.0 Authentication
POST /oauth/token
Content-Type: application/x-www-form-urlencoded

grant_type=client_credentials&
client_id=example-client&
client_secret=example-secret

# Response
{
  "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "token_type": "Bearer",
  "expires_in": 3600
}
```

## 4. Implementation Guidelines

### 4.1 Resource Profiles
```json
{
  "resourceType": "StructureDefinition",
  "id": "angaza-patient",
  "url": "http://example.org/fhir/StructureDefinition/angaza-patient",
  "name": "AngazaPatient",
  "status": "active",
  "kind": "resource",
  "abstract": false,
  "type": "Patient",
  "baseDefinition": "http://hl7.org/fhir/StructureDefinition/Patient",
  "differential": {
    "element": [
      {
        "id": "Patient.identifier",
        "min": 1,
        "max": "1"
      },
      {
        "id": "Patient.name",
        "min": 1,
        "max": "1"
      }
    ]
  }
}
```

### 4.2 Terminology Standards
```json
{
  "resourceType": "ValueSet",
  "id": "angaza-referral-reasons",
  "url": "http://example.org/fhir/ValueSet/angaza-referral-reasons",
  "name": "AngazaReferralReasons",
  "status": "active",
  "compose": {
    "include": [
      {
        "system": "http://snomed.info/sct",
        "concept": [
          {
            "code": "123456789",
            "display": "Hypertension"
          },
          {
            "code": "987654321",
            "display": "Diabetes"
          }
        ]
      }
    ]
  }
}
```

## 5. Testing Requirements

### 5.1 Conformance Testing
```python
# Example test case for Patient resource validation
def test_patient_resource_validation():
    patient = {
        "resourceType": "Patient",
        "id": "test-patient",
        "name": [{"family": "Test", "given": ["User"]}]
    }
    
    # Validate against profile
    validator = FHIRValidator()
    result = validator.validate(patient, "angaza-patient")
    assert result.is_valid
```

### 5.2 Integration Testing
```python
# Example test case for referral creation
def test_create_referral():
    referral = {
        "resourceType": "ReferralRequest",
        "status": "active",
        "subject": {"reference": "Patient/test-patient"},
        "requester": {"reference": "Practitioner/test-practitioner"}
    }
    
    response = client.post("/fhir/ReferralRequest", json=referral)
    assert response.status_code == 201
    assert response.json()["id"] is not None
```

## 6. Maintenance and Updates

### 6.1 Version Management
```yaml
# Example version control configuration
version: 1.0.0
fhir_version: 4.0.1
profiles:
  - name: angaza-patient
    version: 1.0.0
    status: active
  - name: angaza-practitioner
    version: 1.0.0
    status: active
```

### 6.2 Documentation
```markdown
# API Documentation Example

## Patient Resource
The Patient resource represents a patient in the system.

### Fields
- `id`: Unique identifier
- `name`: Patient's full name
- `gender`: Patient's gender
- `birthDate`: Date of birth

### Example
```json
{
  "resourceType": "Patient",
  "id": "example",
  "name": [{"family": "Smith", "given": ["John"]}]
}
```
```

## 7. Monitoring and Reporting

### 7.1 Performance Monitoring
```python
# Example monitoring metrics
metrics = {
    "api_calls": {
        "total": 1000,
        "success": 950,
        "error": 50
    },
    "response_times": {
        "average": 150,  # ms
        "p95": 300,      # ms
        "p99": 500       # ms
    }
}
```

### 7.2 Quality Monitoring
```python
# Example quality metrics
quality_metrics = {
    "data_quality": {
        "completeness": 0.95,
        "accuracy": 0.98,
        "consistency": 0.97
    },
    "compliance": {
        "profile_adherence": 0.99,
        "terminology_usage": 0.98
    }
}
``` 