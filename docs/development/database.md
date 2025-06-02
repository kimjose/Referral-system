# Database Documentation

This document describes the database structure and relationships in the Angaza Referral System.

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'provider', 'staff') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Patients Table
```sql
CREATE TABLE patients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mrn VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Referrals Table
```sql
CREATE TABLE referrals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id BIGINT UNSIGNED NOT NULL,
    from_facility_id BIGINT UNSIGNED NOT NULL,
    to_facility_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending', 'accepted', 'rejected', 'completed') NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL,
    reason TEXT NOT NULL,
    notes TEXT,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (from_facility_id) REFERENCES facilities(id),
    FOREIGN KEY (to_facility_id) REFERENCES facilities(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### Facilities Table
```sql
CREATE TABLE facilities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('hospital', 'clinic', 'specialist') NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Relationships

- Users can create multiple referrals
- Patients can have multiple referrals
- Facilities can be source or destination for referrals
- Each referral has one patient, one source facility, and one destination facility

## Indexes

- `users.email` - For quick user lookup
- `patients.mrn` - For quick patient lookup
- `referrals.patient_id` - For patient referral history
- `referrals.status` - For status-based queries
- `facilities.type` - For facility type filtering

## Data Types

- Use `BIGINT UNSIGNED` for IDs
- Use `VARCHAR(255)` for names and emails
- Use `TEXT` for long-form content
- Use `ENUM` for fixed-choice fields
- Use `TIMESTAMP` for dates

## Best Practices

1. Always use foreign key constraints
2. Index frequently queried columns
3. Use appropriate data types
4. Include created_at and updated_at timestamps
5. Use ENUM for status and type fields

## Database Management

### Backup Procedures
1. Daily automated backups
2. Weekly full backups
3. Monthly archive backups

### Maintenance Tasks
1. Regular index optimization
2. Table statistics updates
3. Log rotation

## Security Measures

### Data Protection
- Password hashing using bcrypt
- Encrypted sensitive data
- Regular security audits

### Access Control
- Role-based access control
- IP whitelisting
- Connection encryption

## Performance Optimization

### Indexing Strategy
- Primary keys on all tables
- Foreign key indexes
- Composite indexes for common queries

### Query Optimization
- Prepared statements
- Query caching
- Connection pooling

## Migration Management

### Creating Migrations
```bash
php artisan make:migration create_table_name
```

### Running Migrations
```bash
php artisan migrate
```

### Rolling Back Migrations
```bash
php artisan migrate:rollback
```

## Database Monitoring

### Key Metrics
- Query performance
- Connection pool usage
- Disk space utilization
- Backup status

### Monitoring Tools
- MySQL Workbench
- Custom monitoring dashboard
- Error logging system

## Troubleshooting

### Common Issues
1. Connection timeouts
2. Deadlocks
3. Performance bottlenecks

### Solutions
1. Connection pool tuning
2. Query optimization
3. Index maintenance

## Best Practices

### Development
1. Use migrations for schema changes
2. Implement proper indexing
3. Follow naming conventions
4. Document all changes

### Production
1. Regular backups
2. Performance monitoring
3. Security updates
4. Capacity planning 