# Database Migrations

## Overview

This document describes the database migrations used in the Angaza Referral System. Migrations are used to create and modify database tables.

## Core Migrations

### Users Table

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('role');
    $table->foreignId('facility_id')->constrained();
    $table->rememberToken();
    $table->timestamps();
});
```

### Facilities Table

```php
Schema::create('facilities', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique();
    $table->string('address');
    $table->string('phone');
    $table->string('email');
    $table->timestamps();
});
```

### Referrals Table

```php
Schema::create('referrals', function (Blueprint $table) {
    $table->id();
    $table->string('patient_name');
    $table->string('patient_phone');
    $table->foreignId('from_facility_id')->constrained('facilities');
    $table->foreignId('to_facility_id')->constrained('facilities');
    $table->string('status');
    $table->text('notes')->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
});
```

### Referral Logs Table

```php
Schema::create('referral_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('referral_id')->constrained();
    $table->string('status');
    $table->text('notes')->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
});
```

## Running Migrations

### Fresh Migration

To run all migrations from scratch:

```bash
php artisan migrate:fresh
```

### Rollback

To rollback the last migration:

```bash
php artisan migrate:rollback
```

### Status

To check migration status:

```bash
php artisan migrate:status
```

## Migration Best Practices

1. **Naming Conventions**
   - Use descriptive names
   - Include timestamp
   - Follow Laravel naming pattern

2. **Foreign Keys**
   - Always add foreign key constraints
   - Use onDelete and onUpdate where appropriate
   - Index foreign key columns

3. **Data Types**
   - Use appropriate data types
   - Consider storage requirements
   - Plan for future growth

4. **Indexes**
   - Add indexes for frequently queried columns
   - Consider composite indexes
   - Don't over-index

## Common Operations

### Adding Columns

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('phone')->nullable();
});
```

### Modifying Columns

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('name', 100)->change();
});
```

### Dropping Columns

```php
Schema::table('users', function (Blueprint $table) {
    $table->dropColumn('phone');
});
```

### Adding Indexes

```php
Schema::table('referrals', function (Blueprint $table) {
    $table->index('status');
});
```

## Data Seeding

### Creating Seeders

```bash
php artisan make:seeder UserSeeder
```

### Running Seeders

```bash
php artisan db:seed
```

### Specific Seeder

```bash
php artisan db:seed --class=UserSeeder
```

## Maintenance

### Backup

Before running migrations:

```bash
php artisan backup:run
```

### Verification

After migrations:

```bash
php artisan migrate:verify
```

## Troubleshooting

### Common Issues

1. **Foreign Key Constraints**
   - Check table order
   - Verify column types
   - Ensure data consistency

2. **Column Type Mismatches**
   - Verify data types
   - Check for data loss
   - Use appropriate conversions

3. **Index Issues**
   - Check index names
   - Verify column existence
   - Consider performance impact 