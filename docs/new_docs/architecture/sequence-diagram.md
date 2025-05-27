# Sequence Diagram

This document shows the sequence of interactions between different components during key processes in the Angaza Referral System.

## Referral Creation Process

```mermaid
sequenceDiagram
    participant U as User
    participant C as Controller
    participant M as Model
    participant DB as Database
    participant S as SMS Service

    U->>C: Submit Referral Form
    C->>M: Create Referral
    M->>DB: Save Referral Data
    DB-->>M: Confirm Save
    M->>M: Create Initial Log
    M->>DB: Save Log
    M->>S: Send SMS Notification
    S-->>M: SMS Sent
    M-->>C: Referral Created
    C-->>U: Show Success Message
```

## Referral Status Update Process

```mermaid
sequenceDiagram
    participant U as User
    participant C as Controller
    participant M as Model
    participant DB as Database
    participant S as SMS Service

    U->>C: Update Referral Status
    C->>M: Update Status
    M->>DB: Update Referral
    DB-->>M: Confirm Update
    M->>M: Create Status Log
    M->>DB: Save Log
    M->>S: Send Status SMS
    S-->>M: SMS Sent
    M-->>C: Status Updated
    C-->>U: Show Success Message
```

## User Authentication Process

```mermaid
sequenceDiagram
    participant U as User
    participant C as Controller
    participant A as Auth Service
    participant DB as Database

    U->>C: Submit Login Form
    C->>A: Authenticate
    A->>DB: Verify Credentials
    DB-->>A: User Data
    A->>A: Generate Token
    A-->>C: Auth Success
    C-->>U: Redirect to Dashboard
```

## Key Processes

1. **Referral Creation**
   - Form submission
   - Data validation
   - Database storage
   - Log creation
   - SMS notification

2. **Status Updates**
   - Status change request
   - Database update
   - Log creation
   - SMS notification
   - UI update

3. **Authentication**
   - Credential submission
   - Verification
   - Token generation
   - Session creation
   - Dashboard access

## Process Notes

1. **Data Validation**
   - All inputs are validated before processing
   - Validation rules are defined in the models
   - Error messages are returned for invalid data

2. **Error Handling**
   - Database errors are caught and logged
   - User-friendly error messages are displayed
   - Failed operations are rolled back

3. **Notifications**
   - SMS notifications are sent asynchronously
   - Email notifications are queued
   - System logs are maintained for all operations 