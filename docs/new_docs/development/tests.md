# Testing Documentation

## Overview

This document describes the testing strategy and test cases for the Angaza Referral System.

## Test Types

### Unit Tests

Unit tests focus on testing individual components in isolation.

#### Model Tests

```php
class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
    }

    public function test_user_has_facility()
    {
        $facility = Facility::factory()->create();
        $user = User::factory()->create(['facility_id' => $facility->id]);

        $this->assertInstanceOf(Facility::class, $user->facility);
    }
}
```

#### Service Tests

```php
class ReferralServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_referral()
    {
        $service = new ReferralService();
        $referral = $service->create([
            'patient_name' => 'John Doe',
            'from_facility_id' => 1,
            'to_facility_id' => 2
        ]);

        $this->assertInstanceOf(Referral::class, $referral);
        $this->assertEquals('pending', $referral->status);
    }
}
```

### Feature Tests

Feature tests verify that multiple components work together correctly.

#### Authentication Tests

```php
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ]);

        $response = $this->post('/user-login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }
}
```

#### Referral Tests

```php
class ReferralTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_referral()
    {
        $user = User::factory()->create();
        $fromFacility = Facility::factory()->create();
        $toFacility = Facility::factory()->create();

        $response = $this->actingAs($user)->post('/referral/save/tab1', [
            'patient_name' => 'John Doe',
            'from_facility_id' => $fromFacility->id,
            'to_facility_id' => $toFacility->id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('referrals', [
            'patient_name' => 'John Doe'
        ]);
    }
}
```

### Integration Tests

Integration tests verify that the system works with external services.

#### MFL Integration Tests

```php
class MFLIntegrationTest extends TestCase
{
    public function test_can_fetch_facilities()
    {
        $response = $this->get('/mfl/facilities/by_service?service_id=1');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'code'
                ]
            ]
        ]);
    }
}
```

## Test Environment

### Configuration

The test environment uses:
- SQLite in-memory database
- Mocked external services
- Test-specific environment variables

### Running Tests

Run all tests:
```bash
php artisan test
```

Run specific test:
```bash
php artisan test --filter=UserTest
```

Run with coverage:
```bash
php artisan test --coverage
```

## Test Data

### Factories

Factories are used to generate test data:

```php
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'role' => 'user',
            'facility_id' => Facility::factory()
        ];
    }
}
```

### Seeders

Seeders populate the database with test data:

```php
class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test facilities
        Facility::factory()->count(5)->create();

        // Create test users
        User::factory()->count(10)->create();

        // Create test referrals
        Referral::factory()->count(20)->create();
    }
}
```

## Continuous Integration

Tests are automatically run:
- On pull requests
- Before merging to main
- On deployment

## Best Practices

1. **Test Isolation**
   - Each test should be independent
   - Use database transactions
   - Clean up after tests

2. **Test Coverage**
   - Aim for high coverage
   - Focus on critical paths
   - Test edge cases

3. **Test Organization**
   - Group related tests
   - Use descriptive names
   - Follow AAA pattern (Arrange, Act, Assert)

4. **Test Data**
   - Use factories
   - Keep test data minimal
   - Use meaningful test data 