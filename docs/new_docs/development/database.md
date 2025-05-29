# Database Documentation

## Overview
The Angaza Referral System uses MySQL as its primary database system. This documentation covers database structure, relationships, and management.

## Database Schema

### Core Tables

#### Users Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'agent', 'user') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### Referrals Table
```sql
CREATE TABLE referrals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    referrer_id BIGINT UNSIGNED NOT NULL,
    referred_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL,
    points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (referrer_id) REFERENCES users(id),
    FOREIGN KEY (referred_id) REFERENCES users(id)
);
```

#### Transactions Table
```sql
CREATE TABLE transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('referral_bonus', 'redemption', 'adjustment') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## Database Relationships

### One-to-Many Relationships
- Users to Referrals (One user can have many referrals)
- Users to Transactions (One user can have many transactions)

### Many-to-Many Relationships
- Users to Rewards (Through redemptions)

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