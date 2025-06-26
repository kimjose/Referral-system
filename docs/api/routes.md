# Routes Documentation

This section documents the main web and API routes in the Angaza Referral System.

## Web Routes

| Method | URI                        | Handler (Controller@method)         | Description                       |
|--------|----------------------------|-------------------------------------|-----------------------------------|
| GET    | /                          | UserController@signIn               | Show login form                   |
| POST   | /user-login                | UserController@login                | Handle user login                 |
| GET    | /dashboard                 | UserController@dashboard            | User dashboard                    |
| GET    | /admin                     | UserController@admin                | Admin dashboard                   |
| GET    | /doctor                    | UserController@doctor               | Doctor dashboard                  |
| GET    | /logout                    | UserController@logout               | Logout user                       |
| GET    | /facilities                | ReferralController@facilities       | List facilities                   |
| POST   | /submit-referral           | ReferralController@submitReferral   | Submit a referral                 |
| ...    | ...                        | ...                                 | ...                               |

## API Routes

| Method | URI                                 | Handler (Controller@method)         | Description                       |
|--------|-------------------------------------|-------------------------------------|-----------------------------------|
| GET    | /api/mfl/facilities                 | MFLController@getFacilities         | List all facilities               |
| GET    | /api/mfl/facilities/{id}            | MFLController@getFacility           | Get facility by ID                |
| GET    | /api/mfl/community-health-units     | MFLController@getCommunityHealthUnits| List community health units      |
| POST   | /api/mfl/sync                       | MFLController@syncFacilities        | Sync facilities                   |
| POST   | /api/webhooks/echis                 | ECHISWebhookController@handle       | Handle ECHIS webhook              |
| ...    | ...                                 | ...                                 | ...                               |

(Expand with more routes as needed)

