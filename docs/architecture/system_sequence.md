# System Sequence Documentation

## Overview
The Angaza Referral System is designed to manage referrals between healthcare facilities and community health units (CHUs). This document outlines the key system sequences and workflows.

## Core Sequences

### 1. Facility-CHU Integration
1. **Initial Setup**
   - System connects to MFL API
   - Fetches facility data
   - Maps facilities to CHUs
   - Stores relationship data

2. **Synchronization Process**
   - Daily sync at 01:00 AM
   - Updates facility information
   - Updates CHU information
   - Maintains relationship mappings

### 2. Referral Management
1. **Referral Creation**
   - Healthcare worker initiates referral
   - System validates facility/CHU data
   - Generates unique referral ID
   - Stores referral details

2. **Referral Processing**
   - Receiving facility acknowledges
   - Updates referral status
   - Notifies relevant parties
   - Tracks referral progress

### 3. Data Synchronization
1. **MFL Integration**
   - Daily sync at 01:00 AM
   - Updates facility data
   - Maintains facility-CHU relationships

2. **eCHIS Integration**
   - 15-minute interval sync
   - Updates referral status
   - Syncs patient data

3. **SHR Integration**
   - 30-minute interval sync
   - Updates health records
   - Maintains patient history

4. **HIE Integration**
   - Hourly sync
   - Exchanges health information
   - Updates patient records

## Error Handling
1. **Sync Failures**
   - Logs error details
   - Retries failed operations
   - Notifies administrators
   - Maintains data integrity

2. **Validation Errors**
   - Validates input data
   - Returns detailed error messages
   - Prevents invalid data entry

## Security Measures
1. **Authentication**
   - User authentication
   - Role-based access control
   - Session management

2. **Data Protection**
   - Encrypted data transmission
   - Secure storage
   - Access logging

## Monitoring and Logging
1. **System Monitoring**
   - Sync status tracking
   - Performance monitoring
   - Error tracking

2. **Audit Logging**
   - User actions
   - System changes
   - Data modifications 