# API Documentation

## Controllers

This section documents the API controllers and their endpoints.

### Authentication Controller

Handles user authentication and authorization.

#### Endpoints

- `POST /api/auth/login`
  - Authenticates a user and returns a JWT token
  - Required fields: email, password

- `POST /api/auth/logout`
  - Logs out the current user
  - Requires authentication

### Referral Controller

Manages patient referrals between facilities.

#### Endpoints

- `GET /api/referrals`
  - Lists all referrals
  - Supports filtering and pagination

- `POST /api/referrals`
  - Creates a new referral
  - Required fields: patient_id, referring_facility_id, receiving_facility_id

- `GET /api/referrals/{id}`
  - Gets details of a specific referral

- `PUT /api/referrals/{id}`
  - Updates a referral
  - Supports partial updates

- `DELETE /api/referrals/{id}`
  - Deletes a referral
  - Requires appropriate permissions

### Facility Controller

Manages healthcare facilities.

#### Endpoints

- `GET /api/facilities`
  - Lists all facilities
  - Supports filtering and pagination

- `POST /api/facilities`
  - Creates a new facility
  - Required fields: name, type, location

- `GET /api/facilities/{id}`
  - Gets details of a specific facility

- `PUT /api/facilities/{id}`
  - Updates a facility
  - Supports partial updates

## Response Format

All API responses follow this format:

```json
{
    "status": "success|error",
    "data": {
        // Response data
    },
    "message": "Optional message",
    "errors": [] // Only present on error
}
```

## Authentication

Most endpoints require authentication using JWT tokens. Include the token in the Authorization header:

```
Authorization: Bearer <your-token>
```

## Error Codes

- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Server Error 