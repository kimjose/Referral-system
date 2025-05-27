# Class Diagram

This document shows the relationships between the main models in the Angaza Referral System.

```mermaid
classDiagram
    class User {
        +String name
        +String email
        +String password
        +String role
        +Integer facility_id
        +belongsTo(Facility)
        +hasMany(Referral)
        +hasMany(ReferralLog)
        +isAdmin()
        +isFacilityUser()
    }

    class Facility {
        +String name
        +String code
        +String address
        +String phone
        +String email
        +hasMany(User)
        +hasMany(Referral)
        +hasMany(Referral)
    }

    class Referral {
        +String patient_name
        +String patient_phone
        +Integer from_facility_id
        +Integer to_facility_id
        +String status
        +String notes
        +Integer created_by
        +belongsTo(Facility)
        +belongsTo(Facility)
        +belongsTo(User)
        +hasMany(ReferralLog)
        +scopePending()
        +scopeAccepted()
        +scopeRejected()
        +scopeCompleted()
    }

    class ReferralLog {
        +Integer referral_id
        +String status
        +String notes
        +Integer created_by
        +belongsTo(Referral)
        +belongsTo(User)
    }

    User "1" -- "1" Facility : belongs to
    User "1" -- "*" Referral : creates
    User "1" -- "*" ReferralLog : creates
    Facility "1" -- "*" User : has many
    Facility "1" -- "*" Referral : sends
    Facility "1" -- "*" Referral : receives
    Referral "1" -- "*" ReferralLog : has many
    Referral "1" -- "1" User : created by
    ReferralLog "1" -- "1" User : created by
```

## Model Relationships

### User Model
- Belongs to one `Facility`
- Has many `Referrals` (created)
- Has many `ReferralLogs` (created)

### Facility Model
- Has many `Users`
- Has many `Referrals` (outgoing)
- Has many `Referrals` (incoming)

### Referral Model
- Belongs to `Facility` (from)
- Belongs to `Facility` (to)
- Belongs to `User` (creator)
- Has many `ReferralLogs`

### ReferralLog Model
- Belongs to `Referral`
- Belongs to `User` (creator)

## Key Features

1. **User Management**
   - Role-based access control
   - Facility association
   - Authentication and authorization

2. **Facility Management**
   - Facility details
   - User associations
   - Referral tracking

3. **Referral Management**
   - Patient information
   - Facility routing
   - Status tracking
   - Logging

4. **Logging System**
   - Status changes
   - User actions
   - Audit trail 