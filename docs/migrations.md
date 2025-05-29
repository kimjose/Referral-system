# Migration Documentation

## Overview
This document outlines the database migrations for the Angaza Referral System, including table creation, modifications, and data seeding.

## Migration Files

### 1. Create Facilities Table
```php
// database/migrations/2024_03_21_000001_create_facilities_table.php
public function up()
{
    Schema::create('facilities', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code')->unique();
        $table->string('status')->default('active');
        $table->timestamps();
    });
}
```

### 2. Create Facility Addresses Table
```php
// database/migrations/2024_03_21_000002_create_facility_addresses_table.php
public function up()
{
    Schema::create('facility_addresses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('facility_id')->constrained()->onDelete('cascade');
        $table->string('address');
        $table->string('county');
        $table->string('sub_county');
        $table->string('ward');
        $table->timestamps();
    });
}
```

### 3. Create Facility Contacts Table
```php
// database/migrations/2024_03_21_000003_create_facility_contacts_table.php
public function up()
{
    Schema::create('facility_contacts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('facility_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->string('phone');
        $table->string('email');
        $table->timestamps();
    });
}
```

### 4. Create Facility Services Table
```php
// database/migrations/2024_03_21_000004_create_facility_services_table.php
public function up()
{
    Schema::create('facility_services', function (Blueprint $table) {
        $table->id();
        $table->foreignId('facility_id')->constrained()->onDelete('cascade');
        $table->string('service_name');
        $table->string('service_code');
        $table->string('status')->default('active');
        $table->timestamps();
    });
}
```

### 5. Create Community Health Units Table
```php
// database/migrations/2024_03_21_000005_create_community_health_units_table.php
public function up()
{
    Schema::create('community_health_units', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code')->unique();
        $table->foreignId('facility_id')->constrained()->onDelete('cascade');
        $table->string('status')->default('active');
        $table->timestamps();
    });
}
```

### 6. Create Community Health Workers Table
```php
// database/migrations/2024_03_21_000006_create_community_health_workers_table.php
public function up()
{
    Schema::create('community_health_workers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone');
        $table->string('email');
        $table->foreignId('chu_id')->constrained()->onDelete('cascade');
        $table->string('status')->default('active');
        $table->timestamps();
    });
}
```

### 7. Create Sync Logs Table
```php
// database/migrations/2024_03_21_000007_create_sync_logs_table.php
public function up()
{
    Schema::create('sync_logs', function (Blueprint $table) {
        $table->id();
        $table->string('type');
        $table->string('status');
        $table->text('message');
        $table->timestamps();
        
        $table->index('type');
        $table->index('status');
        $table->index('created_at');
    });
}
```

## Running Migrations

### Fresh Migration
```bash
php artisan migrate:fresh
```

### With Seeders
```bash
php artisan migrate:fresh --seed
```

### Specific Migration
```bash
php artisan migrate --path=database/migrations/2024_03_21_000001_create_facilities_table.php
```

## Rollback

### Rollback Last Migration
```bash
php artisan migrate:rollback
```

### Rollback All Migrations
```bash
php artisan migrate:reset
```

## Seeding

### Development Data
```bash
php artisan db:seed --class=TestDataSeeder
```

### Production Data
```bash
php artisan db:seed --class=ProductionDataSeeder
```

## Migration Best Practices

1. **Naming Conventions**
   - Use descriptive names
   - Include timestamp prefix
   - Use snake_case
   - Example: `2024_03_21_000001_create_facilities_table.php`

2. **Table Structure**
   - Include `id` as primary key
   - Include `timestamps()`
   - Use appropriate column types
   - Add necessary indexes

3. **Foreign Keys**
   - Use `foreignId()` for relationships
   - Add `onDelete()` constraints
   - Add `onUpdate()` constraints if needed

4. **Indexes**
   - Add indexes for frequently queried columns
   - Add indexes for foreign keys
   - Add indexes for sorting columns

5. **Data Types**
   - Use appropriate string lengths
   - Use appropriate numeric types
   - Use appropriate date/time types

6. **Constraints**
   - Add unique constraints where needed
   - Add not null constraints where needed
   - Add default values where appropriate

7. **Documentation**
   - Document complex migrations
   - Document data transformations
   - Document dependencies

## Common Issues

### 1. Foreign Key Constraints
```php
// Add foreign key with custom name
$table->foreignId('facility_id')
    ->constrained('facilities', 'id')
    ->onDelete('cascade')
    ->name('fk_facility_addresses_facility_id');
```

### 2. Index Creation
```php
// Add index with custom name
$table->index(['county', 'sub_county'], 'idx_facility_location');
```

### 3. Column Modifications
```php
// Modify column type
$table->string('phone', 20)->change();

// Add column after specific column
$table->string('email')->after('phone');
```

### 4. Data Transformations
```php
// Transform data during migration
DB::table('facilities')->update([
    'status' => DB::raw('CASE WHEN active = 1 THEN "active" ELSE "inactive" END')
]);
```

## Migration Testing

### 1. Test Migration Up
```bash
php artisan migrate --path=database/migrations/2024_03_21_000001_create_facilities_table.php
```

### 2. Test Migration Down
```bash
php artisan migrate:rollback --path=database/migrations/2024_03_21_000001_create_facilities_table.php
```

### 3. Test Data Seeding
```bash
php artisan db:seed --class=TestDataSeeder
```

## Production Considerations

1. **Backup**
   - Backup database before migration
   - Test migration on staging
   - Have rollback plan

2. **Performance**
   - Run migrations during low traffic
   - Use appropriate indexes
   - Consider large datasets

3. **Security**
   - Use appropriate permissions
   - Validate input data
   - Sanitize output data

4. **Monitoring**
   - Monitor migration progress
   - Monitor database performance
   - Monitor error logs 