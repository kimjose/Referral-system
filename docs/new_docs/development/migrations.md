# Database Migrations

## Overview
This documentation covers the database migration system used in the Angaza Referral System. Migrations are used to version control the database schema and make it easy to share and deploy database changes.

## Creating Migrations

### Basic Migration
```bash
php artisan make:migration create_users_table
```

### Migration Structure
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
            $table->string('name');
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

## Migration Types

### Table Creation
```php
Schema::create('referrals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('referrer_id')->constrained('users');
    $table->string('referred_email');
    $table->enum('status', ['pending', 'approved', 'rejected']);
    $table->timestamps();
});
```

### Table Modification
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('phone')->nullable();
    $table->index('email');
});
```

### Column Modification
```php
Schema::table('referrals', function (Blueprint $table) {
    $table->string('status')->default('pending')->change();
});
```

## Running Migrations

### Basic Commands
```bash
# Run all pending migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Refresh all migrations
php artisan migrate:refresh

# Refresh and seed
php artisan migrate:refresh --seed
```

### Migration Status
```bash
# Check migration status
php artisan migrate:status

# List all migrations
php artisan migrate:list
```

## Migration Best Practices

### Naming Conventions
1. Use descriptive names
2. Include table name
3. Use past tense
4. Follow Laravel conventions

### Version Control
1. Commit migrations with related code
2. Never modify existing migrations
3. Create new migrations for changes
4. Document complex changes

### Data Integrity
1. Use foreign key constraints
2. Set appropriate indexes
3. Define default values
4. Handle null values

## Common Operations

### Adding Columns
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('avatar')->nullable();
    $table->boolean('is_active')->default(true);
});
```

### Modifying Columns
```php
Schema::table('referrals', function (Blueprint $table) {
    $table->string('status', 20)->change();
});
```

### Dropping Columns
```php
Schema::table('users', function (Blueprint $table) {
    $table->dropColumn('old_column');
});
```

### Adding Indexes
```php
Schema::table('referrals', function (Blueprint $table) {
    $table->index(['referrer_id', 'status']);
});
```

## Complex Migrations

### Conditional Migrations
```php
if (Schema::hasTable('users')) {
    Schema::table('users', function (Blueprint $table) {
        $table->string('new_column');
    });
}
```

### Data Migration
```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('new_column');
    });

    DB::table('users')->update([
        'new_column' => DB::raw('old_column')
    ]);
}
```

## Troubleshooting

### Common Issues
1. Migration conflicts
2. Data type mismatches
3. Foreign key constraints
4. Index issues

### Solutions
1. Check migration order
2. Verify data types
3. Review constraints
4. Test migrations

## Production Considerations

### Safe Deployment
1. Backup database
2. Test migrations
3. Schedule maintenance
4. Monitor execution

### Performance
1. Batch large migrations
2. Use appropriate indexes
3. Optimize queries
4. Monitor impact

## Migration Maintenance

### Cleaning Up
1. Remove old migrations
2. Archive completed migrations
3. Update documentation
4. Review dependencies

### Documentation
1. Document schema changes
2. Update ERD
3. Note breaking changes
4. Track dependencies 