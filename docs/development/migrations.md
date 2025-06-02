# Database Migrations

This document describes the database migrations used in the Angaza Referral System.

## Migration Structure

### Create Users Table
```php
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->enum('role', ['admin', 'provider', 'staff']);
        $table->timestamps();
    });
}
```

### Create Patients Table
```php
public function up()
{
    Schema::create('patients', function (Blueprint $table) {
        $table->id();
        $table->string('mrn')->unique();
        $table->string('first_name');
        $table->string('last_name');
        $table->date('date_of_birth');
        $table->enum('gender', ['male', 'female', 'other']);
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->text('address')->nullable();
        $table->timestamps();
    });
}
```

### Create Referrals Table
```php
public function up()
{
    Schema::create('referrals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained();
        $table->foreignId('from_facility_id')->constrained('facilities');
        $table->foreignId('to_facility_id')->constrained('facilities');
        $table->enum('status', ['pending', 'accepted', 'rejected', 'completed']);
        $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
        $table->text('reason');
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->constrained('users');
        $table->timestamps();
    });
}
```

## Running Migrations

### Development
```bash
php artisan migrate
```

### Production
```bash
php artisan migrate --force
```

### Rollback
```bash
php artisan migrate:rollback
```

## Migration Best Practices

1. Always use timestamps
2. Include foreign key constraints
3. Use appropriate column types
4. Add indexes for performance
5. Include rollback methods

## Data Seeding

### Development Data
```php
public function run()
{
    User::factory()->create([
        'email' => 'admin@example.com',
        'role' => 'admin'
    ]);

    Facility::factory()->count(5)->create();
}
```

### Production Data
```php
public function run()
{
    // Seed essential data only
    $this->call([
        RolesSeeder::class,
        SettingsSeeder::class
    ]);
}
```

## Version Control

- Keep migrations in version control
- Never modify existing migrations
- Create new migrations for changes
- Document migration dependencies

## 2024_03_19_create_facilities_table

Table: facilities

- mfl_code (string)
- name (string)
- type (string)
- status (string)
- county (string)
- sub_county (string)
- ward (string)
## 2024_03_21_create_community_health_units_table

Table: community_health_units

- facility_id (foreignId)
- chu_code (string)
- name (string)
- constituency (string)
- ward (string)
- village (string)
- latitude (decimal)
- longitude (decimal)
- is_active (boolean)
## 2024_03_21_create_community_health_workers_table

Table: community_health_workers

- chu_id (foreignId)
- first_name (string)
- last_name (string)
- phone (string)
- email (string)
- role (string)
- date_of_birth (date)
- gender (string)
- is_active (boolean)
## 2024_03_21_create_facility_addresses_table

Table: facility_addresses

- facility_id (foreignId)
- address_type (string)
- address_line1 (string)
- address_line2 (string)
- city (string)
- state (string)
- postal_code (string)
- country (string)
- latitude (decimal)
- longitude (decimal)
- is_active (boolean)
## 2024_03_21_create_facility_contacts_table

Table: facility_contacts

- facility_id (foreignId)
- contact_type (string)
- contact_value (string)
- is_active (boolean)
## 2024_03_21_create_facility_services_table

Table: facility_services

- facility_id (foreignId)
- service_code (string)
- service_name (string)
- description (text)
- is_active (boolean)
## 2024_03_21_create_sync_logs_table

Table: sync_logs

- type (string)
- status (string)
- message (text)
- created_at (index)
