# API Documentation

## Overview
This document outlines the API endpoints for the Angaza Referral System, including request/response formats, authentication, and error handling.

## Authentication

### Bearer Token Authentication
All API endpoints require Bearer token authentication. Include the token in the Authorization header:
```
Authorization: Bearer <your_token>
```

## Endpoints

### 1. Facility Management

#### List Facilities
```
GET /api/facilities
```

Query Parameters:
- `search` (string, optional): Search by name or code
- `county` (string, optional): Filter by county
- `service` (string, optional): Filter by service
- `page` (integer, optional): Page number for pagination
- `per_page` (integer, optional): Items per page

Response:
```json
{
    "data": [
        {
            "id": 1,
            "name": "Facility Name",
            "code": "FAC001",
            "address": {
                "county": "County Name",
                "sub_county": "Sub County",
                "ward": "Ward Name"
            },
            "contacts": [
                {
                    "name": "Contact Name",
                    "phone": "Phone Number",
                    "email": "email@example.com"
                }
            ],
            "services": [
                {
                    "name": "Service Name",
                    "code": "SVC001"
                }
            ]
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 100
    }
}
```

#### Get Facility Details
```
GET /api/facilities/{id}
```

Response:
```json
{
    "data": {
        "id": 1,
        "name": "Facility Name",
        "code": "FAC001",
        "address": {
            "county": "County Name",
            "sub_county": "Sub County",
            "ward": "Ward Name"
        },
        "contacts": [
            {
                "name": "Contact Name",
                "phone": "Phone Number",
                "email": "email@example.com"
            }
        ],
        "services": [
            {
                "name": "Service Name",
                "code": "SVC001"
            }
        ],
        "chus": [
            {
                "id": 1,
                "name": "CHU Name",
                "code": "CHU001",
                "status": "Active"
            }
        ]
    }
}
```

### 2. CHU Management

#### List CHUs
```
GET /api/chus
```

Query Parameters:
- `facility_id` (integer, optional): Filter by facility
- `search` (string, optional): Search by name or code
- `status` (string, optional): Filter by status
- `page` (integer, optional): Page number for pagination
- `per_page` (integer, optional): Items per page

Response:
```json
{
    "data": [
        {
            "id": 1,
            "name": "CHU Name",
            "code": "CHU001",
            "facility": {
                "id": 1,
                "name": "Facility Name"
            },
            "status": "Active",
            "chws": [
                {
                    "id": 1,
                    "name": "CHW Name",
                    "phone": "Phone Number"
                }
            ]
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 50
    }
}
```

### 3. Integration Sync

#### Trigger Sync
```
POST /api/sync/{type}
```

Parameters:
- `type` (string, required): One of [mfl, echis, shr, hie]

Response:
```json
{
    "message": "Sync started successfully",
    "sync_id": "sync_123"
}
```

#### Get Sync Status
```
GET /api/sync/{sync_id}
```

Response:
```json
{
    "status": "completed",
    "message": "Sync completed successfully",
    "details": {
        "records_processed": 100,
        "records_updated": 50,
        "records_created": 30,
        "errors": 0
    }
}
```

## Error Responses

### 400 Bad Request
```json
{
    "error": "Validation failed",
    "message": "The given data was invalid",
    "errors": {
        "field_name": [
            "Error message"
        ]
    }
}
```

### 401 Unauthorized
```json
{
    "error": "Unauthorized",
    "message": "Invalid or expired token"
}
```

### 404 Not Found
```json
{
    "error": "Not Found",
    "message": "Resource not found"
}
```

### 500 Server Error
```json
{
    "error": "Server Error",
    "message": "An unexpected error occurred"
}
```

## Rate Limiting
- 60 requests per minute per IP address
- Rate limit headers included in response:
  - `X-RateLimit-Limit`
  - `X-RateLimit-Remaining`
  - `X-RateLimit-Reset`

## Versioning
API versioning is handled through the URL:
```
/api/v1/facilities
```

## Data Formats
- All dates are in ISO 8601 format: `YYYY-MM-DDTHH:mm:ssZ`
- All monetary values are in decimal format with 2 decimal places
- All IDs are integers
- All boolean values are represented as `true` or `false` 