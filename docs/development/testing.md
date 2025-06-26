# Testing Documentation

This guide covers the testing strategy and procedures for the Angaza Referral System.

## Test Types

### Unit Tests
- Test individual components in isolation
- Use PHPUnit for PHP code
- Use Jest for JavaScript code
- Run with:
  ```bash
  php artisan test
  npm test
  ```

### Feature Tests
- Test complete features end-to-end
- Use Laravel's testing framework
- Cover API endpoints
- Run with:
  ```bash
  php artisan test --testsuite=Feature
  ```

### Browser Tests
- Test user interface
- Use Laravel Dusk
- Simulate user interactions
- Run with:
  ```bash
  php artisan dusk
  ```

## Test Structure

### PHP Tests
```php
class ReferralTest extends TestCase
{
    public function test_can_create_referral()
    {
        $response = $this->post('/api/referrals', [
            'patient_id' => 1,
            'from_facility_id' => 1,
            'to_facility_id' => 2,
            'reason' => 'Test referral'
        ]);

        $response->assertStatus(201);
    }
}
```

### JavaScript Tests
```javascript
describe('ReferralForm', () => {
    it('submits referral successfully', () => {
        cy.visit('/referrals/new');
        cy.get('[data-test="patient-select"]').select('1');
        cy.get('[data-test="submit"]').click();
        cy.get('[data-test="success-message"]').should('be.visible');
    });
});
```

## Test Data

### Factories
- Use Laravel factories for test data
- Create realistic test scenarios
- Reset database between tests

### Fixtures
- Store common test data
- Use JSON fixtures for API tests
- Maintain test data versioning

## Continuous Integration

### GitHub Actions
```yaml
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Run Tests
        run: |
          composer install
          npm install
          php artisan test
          npm test
```

## Code Coverage

- Aim for 80% code coverage
- Use PHPUnit coverage reports
- Monitor coverage trends
- Focus on critical paths

## Best Practices

1. Write tests before code (TDD)
2. Keep tests independent
3. Use meaningful test names
4. Test edge cases
5. Maintain test documentation

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