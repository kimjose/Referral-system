# Testing Documentation

## Overview
This documentation covers the testing strategy and implementation for the Angaza Referral System. We use PHPUnit for unit testing and Laravel's testing framework for feature and integration tests.

## Test Types

### Unit Tests
Tests individual components in isolation.

#### Example Unit Test
```php
class ReferralTest extends TestCase
{
    public function test_can_create_referral()
    {
        $referral = new Referral([
            'referrer_id' => 1,
            'referred_email' => 'test@example.com'
        ]);
        
        $this->assertEquals('pending', $referral->status);
    }
}
```

### Feature Tests
Tests complete features from a user's perspective.

#### Example Feature Test
```php
class ReferralFeatureTest extends TestCase
{
    public function test_user_can_create_referral()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/referrals', [
                'referred_email' => 'new@example.com'
            ]);
            
        $response->assertStatus(200);
        $this->assertDatabaseHas('referrals', [
            'referrer_id' => $user->id
        ]);
    }
}
```

### Integration Tests
Tests interaction between components.

#### Example Integration Test
```php
class ReferralIntegrationTest extends TestCase
{
    public function test_referral_creates_transaction()
    {
        $referral = Referral::factory()->create();
        
        $referral->approve();
        
        $this->assertDatabaseHas('transactions', [
            'type' => 'referral_bonus',
            'user_id' => $referral->referrer_id
        ]);
    }
}
```

## Test Environment

### Configuration
```php
// phpunit.xml
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

### Database
- Uses SQLite in-memory database
- Migrations run before each test
- Database is reset after each test

## Running Tests

### Command Line
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ReferralTest.php

# Run specific test method
php artisan test --filter test_can_create_referral
```

### Continuous Integration
- Tests run on every push
- Tests run on pull requests
- Coverage reports generated

## Test Coverage

### Coverage Requirements
- Minimum 80% code coverage
- 100% coverage for critical paths
- Regular coverage reports

### Generating Coverage Report
```bash
php artisan test --coverage-html coverage
```

## Mocking

### External Services
```php
public function test_referral_sends_email()
{
    Mail::fake();
    
    $referral = Referral::factory()->create();
    
    Mail::assertSent(ReferralEmail::class);
}
```

### Database
```php
public function test_referral_validation()
{
    $this->mock(ReferralRepository::class, function ($mock) {
        $mock->shouldReceive('create')
            ->once()
            ->andReturn(new Referral());
    });
}
```

## Test Data

### Factories
```php
// database/factories/ReferralFactory.php
class ReferralFactory extends Factory
{
    public function definition()
    {
        return [
            'referrer_id' => User::factory(),
            'referred_email' => $this->faker->email,
            'status' => 'pending'
        ];
    }
}
```

### Seeders
```php
class TestDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ReferralSeeder::class
        ]);
    }
}
```

## Best Practices

### Writing Tests
1. Follow AAA pattern (Arrange, Act, Assert)
2. One assertion per test
3. Use descriptive test names
4. Keep tests independent

### Test Organization
1. Group related tests
2. Use test data providers
3. Share common setup
4. Clean up after tests

## Troubleshooting

### Common Issues
1. Database connection errors
2. Mock failures
3. Timeout issues
4. Memory limits

### Solutions
1. Check environment configuration
2. Verify mock expectations
3. Increase timeout limits
4. Optimize test data

## Performance

### Optimization
1. Use database transactions
2. Minimize external calls
3. Use appropriate assertions
4. Clean up resources

### Monitoring
1. Test execution time
2. Memory usage
3. Database queries
4. Coverage metrics 