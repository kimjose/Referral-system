# Database Schema

## Overview
The Angaza Referral System uses a relational database to store and manage all system data. This document describes the database schema and relationships.

## Core Tables

### Users
```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role_id INTEGER REFERENCES roles(id),
    facility_id INTEGER REFERENCES facilities(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Facilities
```sql
CREATE TABLE facilities (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    contact_info JSONB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Referrals
```sql
CREATE TABLE referrals (
    id SERIAL PRIMARY KEY,
    patient_id INTEGER REFERENCES patients(id),
    referring_facility_id INTEGER REFERENCES facilities(id),
    receiving_facility_id INTEGER REFERENCES facilities(id),
    status VARCHAR(50) NOT NULL,
    priority VARCHAR(20) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Patients
```sql
CREATE TABLE patients (
    id SERIAL PRIMARY KEY,
    mrn VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender VARCHAR(10) NOT NULL,
    contact_info JSONB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Relationships

### One-to-Many
- Facility to Users
- Facility to Referrals (both referring and receiving)
- Patient to Referrals

### Many-to-Many
- Users to Roles (through user_roles)
- Roles to Permissions (through role_permissions)

## Indexes
- Users: username, email
- Facilities: name, type
- Referrals: status, priority
- Patients: mrn, last_name

## Constraints
- Foreign key constraints on all relationships
- Unique constraints on usernames, emails, and MRNs
- NOT NULL constraints on required fields

## Data Types
- SERIAL for auto-incrementing IDs
- VARCHAR for short text
- TEXT for long text
- JSONB for structured data
- TIMESTAMP for dates and times
- INTEGER for foreign keys

## Security
- Password hashing for user passwords
- Role-based access control
- Audit logging for sensitive operations 