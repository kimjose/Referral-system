# Database Schema Documentation

## Overview
This document outlines the database schema for the Angaza Referral System, including tables, relationships, and field descriptions.

## Tables

### 1. community_health_units
Stores information about Community Health Units (CHUs).

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | CHU name |
| code | string | Unique identifier |
| facility_id | bigint | Foreign key to facilities |
| status | string | Active/Inactive |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### 2. community_health_workers
Stores information about Community Health Workers (CHWs).

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | CHW name |
| phone | string | Contact number |
| email | string | Email address |
| chu_id | bigint | Foreign key to CHUs |
| status | string | Active/Inactive |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### 3. facility_addresses
Stores facility location information.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| facility_id | bigint | Foreign key to facilities |
| address | string | Physical address |
| county | string | County name |
| sub_county | string | Sub-county name |
| ward | string | Ward name |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### 4. facility_contacts
Stores facility contact information.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| facility_id | bigint | Foreign key to facilities |
| name | string | Contact person name |
| phone | string | Contact number |
| email | string | Email address |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### 5. facility_services
Stores services offered by facilities.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| facility_id | bigint | Foreign key to facilities |
| service_name | string | Name of service |
| service_code | string | Service identifier |
| status | string | Active/Inactive |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### 6. sync_logs
Tracks synchronization operations.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| type | string | Sync type (MFL/eCHIS/SHR/HIE) |
| status | string | Success/Failure |
| message | text | Sync result message |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

## Relationships

1. **Facility-CHU Relationship**
   - One-to-Many: A facility can have multiple CHUs
   - Foreign key: `community_health_units.facility_id`

2. **CHU-CHW Relationship**
   - One-to-Many: A CHU can have multiple CHWs
   - Foreign key: `community_health_workers.chu_id`

3. **Facility-Address Relationship**
   - One-to-One: A facility has one address
   - Foreign key: `facility_addresses.facility_id`

4. **Facility-Contact Relationship**
   - One-to-Many: A facility can have multiple contacts
   - Foreign key: `facility_contacts.facility_id`

5. **Facility-Service Relationship**
   - One-to-Many: A facility can offer multiple services
   - Foreign key: `facility_services.facility_id`

## Indexes

1. **Primary Indexes**
   - All tables have primary key indexes on `id`

2. **Foreign Key Indexes**
   - `community_health_units.facility_id`
   - `community_health_workers.chu_id`
   - `facility_addresses.facility_id`
   - `facility_contacts.facility_id`
   - `facility_services.facility_id`

3. **Performance Indexes**
   - `community_health_units.code`
   - `facility_services.service_code`
   - `sync_logs.type`
   - `sync_logs.status` 