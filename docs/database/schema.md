# Database Schema Documentation

## Overview

This document describes the database schema for the Angaza Referral System. The system uses MySQL as its database management system.

## Tables

### users

Stores user account information.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | User's full name |
| email | varchar(255) | User's email address (unique) |
| password | varchar(255) | Hashed password |
| role | enum('admin', 'facility', 'user') | User's role in the system |
| facility_id | bigint | Foreign key to facilities table |
| created_at | timestamp | Record creation timestamp |
| updated_at | timestamp | Record last update timestamp |

### facilities

Stores healthcare facility information.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | Facility name |
| code | varchar(50) | Unique facility code |
| address | text | Facility address |
| phone | varchar(20) | Contact phone number |
| email | varchar(255) | Contact email |
| created_at | timestamp | Record creation timestamp |
| updated_at | timestamp | Record last update timestamp |

### referrals

Stores patient referral information.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| patient_name | varchar(255) | Patient's full name |
| patient_phone | varchar(20) | Patient's phone number |
| from_facility_id | bigint | Foreign key to facilities (referring facility) |
| to_facility_id | bigint | Foreign key to facilities (receiving facility) |
| status | enum('pending', 'accepted', 'rejected', 'completed') | Referral status |
| notes | text | Additional notes |
| created_by | bigint | Foreign key to users (who created the referral) |
| created_at | timestamp | Record creation timestamp |
| updated_at | timestamp | Record last update timestamp |

### referral_logs

Stores referral status change history.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| referral_id | bigint | Foreign key to referrals |
| status | enum('pending', 'accepted', 'rejected', 'completed') | New status |
| notes | text | Status change notes |
| created_by | bigint | Foreign key to users (who changed the status) |
| created_at | timestamp | Record creation timestamp |

## Relationships

1. **users to facilities**
   - Many-to-one relationship
   - A user belongs to one facility
   - A facility can have many users

2. **referrals to facilities**
   - Many-to-one relationship with from_facility_id
   - Many-to-one relationship with to_facility_id
   - A facility can have many outgoing and incoming referrals

3. **referrals to users**
   - Many-to-one relationship
   - A user can create many referrals
   - Each referral is created by one user

4. **referral_logs to referrals**
   - Many-to-one relationship
   - A referral can have many status changes
   - Each log entry belongs to one referral

## Indexes

1. **users**
   - Primary key on `id`
   - Unique index on `email`
   - Index on `facility_id`

2. **facilities**
   - Primary key on `id`
   - Unique index on `code`
   - Index on `name`

3. **referrals**
   - Primary key on `id`
   - Index on `from_facility_id`
   - Index on `to_facility_id`
   - Index on `status`
   - Index on `created_by`

4. **referral_logs**
   - Primary key on `id`
   - Index on `referral_id`
   - Index on `created_by`

## Constraints

1. **Foreign Key Constraints**
   - `users.facility_id` references `facilities.id`
   - `referrals.from_facility_id` references `facilities.id`
   - `referrals.to_facility_id` references `facilities.id`
   - `referrals.created_by` references `users.id`
   - `referral_logs.referral_id` references `referrals.id`
   - `referral_logs.created_by` references `users.id`

2. **Unique Constraints**
   - `users.email` must be unique
   - `facilities.code` must be unique

3. **Not Null Constraints**
   - All primary key fields
   - `users.name`, `users.email`, `users.password`, `users.role`
   - `facilities.name`, `facilities.code`
   - `referrals.patient_name`, `referrals.from_facility_id`, `referrals.to_facility_id`, `referrals.status`
   - `referral_logs.referral_id`, `referral_logs.status`, `referral_logs.created_by` 