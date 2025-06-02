# API Documentation

The Angaza Referral System provides a RESTful API for integrating with other healthcare systems.

## Authentication

All API requests require authentication using JWT tokens.

```http
Authorization: Bearer <your_jwt_token>
```

## Base URL

```
https://api.angaza.com/v1
```

## Endpoints

### Referrals

#### Create Referral
```http
POST /referrals
```

#### Get Referral
```http
GET /referrals/{id}
```

#### Update Referral
```http
PUT /referrals/{id}
```

#### List Referrals
```http
GET /referrals
```

### Patients

#### Create Patient
```http
POST /patients
```

#### Get Patient
```http
GET /patients/{id}
```

#### Update Patient
```http
PUT /patients/{id}
```

#### List Patients
```http
GET /patients
```

## Response Format

All responses are in JSON format:

```json
{
  "status": "success",
  "data": {
    // Response data
  },
  "message": "Operation successful"
}
```

## Error Handling

Errors follow this format:

```json
{
  "status": "error",
  "error": {
    "code": "ERROR_CODE",
    "message": "Error description"
  }
}
```

## Rate Limiting

- 100 requests per minute per API key
- Rate limit headers included in responses

## Versioning

API versions are specified in the URL path. Current version is v1.

## API Endpoints

### Facilities

#### List Facilities

```http
GET /facilities
Query Parameters:
- page: Page number (default: 1)
- per_page: Items per page (default: 20)
- search: Search term
- type: Facility type
```

Response:
```json
{
    "data": [
        {
            "id": 1,
            "name": "City Hospital",
            "type": "hospital",
            "address": "123 Main St",
            "contact": "+1234567890",
            "services": ["emergency", "surgery", "pediatrics"]
        }
    ],
    "meta": {
        "current_page": 1,
        "total_pages": 5,
        "total_items": 100
    }
}
```

#### Create Facility

```http
POST /facilities
Content-Type: application/json

{
    "name": "New Medical Center",
    "type": "clinic",
    "address": "789 Pine St",
    "contact": "+1987654321",
    "services": ["general", "pediatrics"],
    "capacity": 50
}
```

### Users

#### Create User

```http
POST /users
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "secure_password",
    "role": "facility_admin",
    "facility_id": 1
}
```

#### Update User

```http
PUT /users/{id}
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "role": "facility_admin"
}
```

## Best Practices

1. **Error Handling**
   - Always check for error responses
   - Implement proper retry logic
   - Handle rate limiting gracefully

2. **Security**
   - Keep JWT tokens secure
   - Use HTTPS for all requests
   - Implement proper token refresh logic

3. **Performance**
   - Use pagination for large data sets
   - Implement caching where appropriate
   - Minimize request payload size

4. **Testing**
   - Use the sandbox environment for testing
   - Test all error scenarios
   - Validate response formats

## Webhooks

The API supports webhooks for real-time notifications. Configure webhooks in your account settings:

```http
POST /webhooks
Content-Type: application/json

{
    "url": "https://your-server.com/webhook",
    "events": ["referral.created", "referral.updated"],
    "secret": "your_webhook_secret"
}
```

## SDKs and Libraries

Official SDKs are available for:
- JavaScript/Node.js
- Python
- PHP
- Java

Example using Node.js SDK:
```javascript
const AngazaAPI = require('@angaza/referral-sdk');

const api = new AngazaAPI({
    apiKey: 'your_api_key',
    environment: 'production'
});

// Create a referral
const referral = await api.referrals.create({
    patient_id: '12345',
    from_facility_id: 1,
    to_facility_id: 2,
    reason: 'Specialized care required'
});
```

## Related Documentation

- [API Controllers](controllers.md)
- [API Models](models.md)
- [API Routes](routes.md)
- [Authentication Guide](authentication.md)
- [Webhook Guide](webhooks.md)
- [SDK Documentation](sdk.md) 