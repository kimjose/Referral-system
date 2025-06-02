# API Documentation

## Overview
The Angaza Referral System provides a RESTful API for interacting with the system programmatically. The API follows REST principles and uses JSON for request and response payloads.

## Authentication
All API requests require authentication using JWT (JSON Web Tokens). Include the token in the Authorization header:
```
Authorization: Bearer <your_jwt_token>
```

## Base URL
```
https://api.angaza-referral.com/v1
```

## Endpoints

### Authentication

#### Login
```http
POST /auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}
```

Response:
```json
{
    "token": "jwt_token_here",
    "user": {
        "id": 1,
        "email": "user@example.com",
        "role": "agent"
    }
}
```

### Users

#### Get User Profile
```http
GET /users/profile
```

Response:
```json
{
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "role": "agent",
    "created_at": "2024-03-20T10:00:00Z"
}
```

### Referrals

#### Create Referral
```http
POST /referrals
Content-Type: application/json

{
    "referred_email": "new_user@example.com",
    "notes": "Optional referral notes"
}
```

Response:
```json
{
    "id": 1,
    "referrer_id": 1,
    "referred_email": "new_user@example.com",
    "status": "pending",
    "created_at": "2024-03-20T10:00:00Z"
}
```

#### List Referrals
```http
GET /referrals
```

Response:
```json
{
    "referrals": [
        {
            "id": 1,
            "referrer_id": 1,
            "referred_id": 2,
            "status": "completed",
            "created_at": "2024-03-20T10:00:00Z"
        }
    ],
    "total": 1,
    "page": 1,
    "per_page": 10
}
```

### Transactions

#### List Transactions
```http
GET /transactions
```

Response:
```json
{
    "transactions": [
        {
            "id": 1,
            "referral_id": 1,
            "amount": 100.00,
            "status": "completed",
            "created_at": "2024-03-20T10:00:00Z"
        }
    ],
    "total": 1,
    "page": 1,
    "per_page": 10
}
```

## Error Handling
The API uses standard HTTP status codes and returns error messages in the following format:

```json
{
    "error": {
        "code": "ERROR_CODE",
        "message": "Human readable error message"
    }
}
```

Common error codes:
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Internal Server Error

## Rate Limiting
API requests are limited to:
- 100 requests per minute for authenticated users
- 20 requests per minute for unauthenticated users

Rate limit headers are included in all responses:
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 99
X-RateLimit-Reset: 1616234400
```

## Pagination
List endpoints support pagination using the following query parameters:
- page: Page number (default: 1)
- per_page: Items per page (default: 10, max: 100)

## Versioning
The API version is included in the URL path. The current version is v1.

## SDKs
Official SDKs are available for:
- Python
- JavaScript/Node.js
- PHP
- Ruby

## Support
For API support, contact:
- Email: api-support@angaza-referral.com
- Documentation: https://docs.angaza-referral.com 