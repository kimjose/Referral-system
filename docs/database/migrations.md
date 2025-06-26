# Database Migrations

## Overview
This document describes the database migration process and available migrations for the Angaza Referral System.

## Migration Process

### Running Migrations
```bash
# Run all pending migrations
npm run migrate

# Rollback last migration
npm run migrate:rollback

# Create new migration
npm run migrate:create
```

## Migration Files

### Initial Schema
- Create users table
- Create facilities table
- Create patients table
- Create referrals table

### Updates
- Add indexes
- Add foreign key constraints
- Add new columns
- Modify existing columns

## Best Practices
- Always backup database before migrations
- Test migrations in development first
- Include rollback functionality
- Document schema changes

## Troubleshooting
- Check migration logs
- Verify database connection
- Ensure proper permissions
- Review error messages

## Migration Tools

### 1. Framework
- Laravel Migrations
- Version control for database schema
- Rollback capabilities
- Seed data management

### 2. Migration Files
- Located in `database/migrations/`
- Timestamp-based naming
- Descriptive file names
- Version controlled

## Migration Structure

### 1. File Format
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
```

### 2. Common Operations
- Create tables
- Modify columns
- Add indexes
- Create relationships
- Drop tables/columns

## Schema Changes

### 1. Adding Columns
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('phone_number')->nullable();
    $table->boolean('is_active')->default(true);
});
```

### 2. Modifying Columns
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('email', 100)->change();
    $table->dropColumn('phone_number');
});
```

### 3. Adding Indexes
```php
Schema::table('referrals', function (Blueprint $table) {
    $table->index('status');
    $table->unique('reference_number');
});
```

## Data Migrations

### 1. Seeding Data
```php
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            FacilitySeeder::class,
            RoleSeeder::class
        ]);
    }
}
```

### 2. Custom Seeders
```php
class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ]);
    }
}
```

## Best Practices

### 1. Migration Design
- Keep migrations small and focused
- Use meaningful names
- Include rollback logic
- Test migrations thoroughly

### 2. Data Integrity
- Validate data before migration
- Handle existing data
- Maintain referential integrity
- Backup before migration

### 3. Performance
- Index large tables
- Batch process data
- Optimize queries
- Monitor execution time

## Environment Management

### 1. Development
- Local database setup
- Test data seeding
- Migration testing
- Development workflow

### 2. Staging
- Production-like data
- Migration verification
- Performance testing
- Rollback testing

### 3. Production
- Backup procedures
- Maintenance windows
- Monitoring
- Rollback plans

## Troubleshooting

### 1. Common Issues
- Migration failures
- Data inconsistencies
- Performance problems
- Lock timeouts

### 2. Solutions
- Check error logs
- Verify database state
- Review migration history
- Test rollback procedures

## Security

### 1. Access Control
- Database permissions
- Migration user roles
- Audit logging
- Access monitoring

### 2. Data Protection
- Sensitive data handling
- Encryption
- Data masking
- Access restrictions

## Monitoring

### 1. Migration Status
- Pending migrations
- Applied migrations
- Failed migrations
- Rollback status

### 2. Performance Metrics
- Execution time
- Resource usage
- Lock duration
- Query performance

## Documentation

### 1. Migration Records
- Migration history
- Schema changes
- Data modifications
- Dependencies

### 2. Process Documentation
- Migration procedures
- Rollback procedures
- Emergency procedures
- Best practices

## Future Improvements

### 1. Planned Enhancements
- Automated testing
- Performance optimization
- Monitoring improvements
- Documentation updates

### 2. Tool Updates
- Framework upgrades
- New features
- Security enhancements
- Process improvements 