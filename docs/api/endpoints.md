# API Endpoints

## Authentication

### POST /api/auth/login
Authenticate a user and return a JWT token.

**Request Body:**
```json
{
    "username": "string",
    "password": "string"
}
```

**Response:**
```json
{
    "token": "string",
    "user": {
        "id": "integer",
        "username": "string",
        "role": "string"
    }
}
```

### POST /api/auth/logout
Invalidate the current JWT token.

**Headers:**
- Authorization: Bearer {token}

**Response:**
```json
{
    "message": "Successfully logged out"
}
```

## Referrals

### GET /api/referrals
Get a list of referrals.

**Query Parameters:**
- status (optional): Filter by status
- facility_id (optional): Filter by facility
- page (optional): Page number
- limit (optional): Items per page

**Response:**
```json
{
    "data": [
        {
            "id": "integer",
            "patient": {
                "id": "integer",
                "name": "string"
            },
            "referring_facility": {
                "id": "integer",
                "name": "string"
            },
            "receiving_facility": {
                "id": "integer",
                "name": "string"
            },
            "status": "string",
            "priority": "string",
            "created_at": "datetime"
        }
    ],
    "meta": {
        "current_page": "integer",
        "total_pages": "integer",
        "total_items": "integer"
    }
}
```

### POST /api/referrals
Create a new referral.

**Request Body:**
```json
{
    "patient_id": "integer",
    "referring_facility_id": "integer",
    "receiving_facility_id": "integer",
    "priority": "string",
    "notes": "string"
}
```

**Response:**
```json
{
    "id": "integer",
    "status": "string",
    "created_at": "datetime"
}
```

### GET /api/referrals/{id}
Get a specific referral.

**Response:**
```json
{
    "id": "integer",
    "patient": {
        "id": "integer",
        "name": "string",
        "dob": "date",
        "gender": "string"
    },
    "referring_facility": {
        "id": "integer",
        "name": "string",
        "type": "string"
    },
    "receiving_facility": {
        "id": "integer",
        "name": "string",
        "type": "string"
    },
    "status": "string",
    "priority": "string",
    "notes": "string",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

### PUT /api/referrals/{id}
Update a referral.

**Request Body:**
```json
{
    "status": "string",
    "priority": "string",
    "notes": "string"
}
```

**Response:**
```json
{
    "id": "integer",
    "status": "string",
    "updated_at": "datetime"
}
```

## Facilities

### GET /api/facilities
Get a list of facilities.

**Query Parameters:**
- type (optional): Filter by facility type
- search (optional): Search by name
- page (optional): Page number
- limit (optional): Items per page

**Response:**
```json
{
    "data": [
        {
            "id": "integer",
            "name": "string",
            "type": "string",
            "address": "string",
            "contact_info": "object"
        }
    ],
    "meta": {
        "current_page": "integer",
        "total_pages": "integer",
        "total_items": "integer"
    }
}
```

### POST /api/facilities
Create a new facility.

**Request Body:**
```json
{
    "name": "string",
    "type": "string",
    "address": "string",
    "contact_info": "object"
}
```

**Response:**
```json
{
    "id": "integer",
    "name": "string",
    "created_at": "datetime"
}
```

## Error Responses

All endpoints may return the following error responses:

### 400 Bad Request
```json
{
    "error": "string",
    "message": "string"
}
```

### 401 Unauthorized
```json
{
    "error": "Unauthorized",
    "message": "Invalid or expired token"
}
```

### 403 Forbidden
```json
{
    "error": "Forbidden",
    "message": "Insufficient permissions"
}
```

### 404 Not Found
```json
{
    "error": "Not Found",
    "message": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
    "error": "Internal Server Error",
    "message": "An unexpected error occurred"
}
``` 