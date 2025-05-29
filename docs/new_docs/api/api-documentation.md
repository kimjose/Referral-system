# API Documentation

## Overview
The Angaza Referral System provides a RESTful API for integration with other systems. This documentation covers all available endpoints, authentication, and usage examples.

## Authentication

### API Key Authentication
All API requests require an API key to be included in the header:
```
Authorization: Bearer your-api-key
```

### Obtaining an API Key
1. Log in to the admin dashboard
2. Navigate to API Settings
3. Generate a new API key

## Endpoints

### Users

#### Get User Profile
```http
GET /api/v1/users/{id}
```
Response:
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "created_at": "2024-03-20T10:00:00Z"
}
```

#### Update User Profile
```http
PUT /api/v1/users/{id}
```
Request Body:
```json
{
    "name": "John Doe",
    "email": "john@example.com"
}
```

### Referrals

#### Create Referral
```http
POST /api/v1/referrals
```
Request Body:
```json
{
    "referrer_id": 1,
    "referred_email": "newuser@example.com"
}
```

#### Get Referral Status
```http
GET /api/v1/referrals/{id}
```
Response:
```json
{
    "id": 1,
    "status": "pending",
    "points": 0,
    "created_at": "2024-03-20T10:00:00Z"
}
```

### Transactions

#### Get User Transactions
```http
GET /api/v1/users/{id}/transactions
```
Response:
```json
{
    "transactions": [
        {
            "id": 1,
            "type": "referral_bonus",
            "amount": 100,
            "created_at": "2024-03-20T10:00:00Z"
        }
    ]
}
```

## Error Handling

### Error Response Format
```json
{
    "error": {
        "code": "ERROR_CODE",
        "message": "Error description",
        "details": {}
    }
}
```

### Common Error Codes
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 429: Too Many Requests
- 500: Internal Server Error

## Rate Limiting

### Limits
- 100 requests per minute per API key
- 1000 requests per hour per API key

### Rate Limit Headers
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 99
X-RateLimit-Reset: 1616234400
```

## Webhooks

### Available Events
- referral.created
- referral.approved
- referral.rejected
- transaction.created

### Webhook Configuration
```http
POST /api/v1/webhooks
```
Request Body:
```json
{
    "url": "https://your-domain.com/webhook",
    "events": ["referral.created", "referral.approved"]
}
```

## SDKs

### PHP SDK
```php
$client = new AngazaReferralClient('your-api-key');
$referral = $client->createReferral([
    'referrer_id' => 1,
    'referred_email' => 'newuser@example.com'
]);
```

### JavaScript SDK
```javascript
const client = new AngazaReferralClient('your-api-key');
const referral = await client.createReferral({
    referrer_id: 1,
    referred_email: 'newuser@example.com'
});
```

## Best Practices

### Security
1. Always use HTTPS
2. Rotate API keys regularly
3. Implement IP whitelisting
4. Monitor API usage

### Performance
1. Implement caching
2. Use pagination
3. Optimize payload size
4. Handle rate limits

### Error Handling
1. Implement retry logic
2. Log all errors
3. Monitor error rates
4. Set up alerts

## Versioning

### Current Version
- API Version: v1
- Release Date: 2024-03-20
- Status: Active

### Version Deprecation
- 6 months notice before deprecation
- 3 months grace period
- Migration guides provided

## Support

### Getting Help
- API Documentation: /api/docs
- Support Email: api-support@angaza.com
- Developer Forum: forum.angaza.com
- Status Page: status.angaza.com 