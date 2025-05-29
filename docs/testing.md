# Testing Documentation

## Overview
This document outlines the testing strategy and procedures for the Angaza Referral System, including unit tests, feature tests, and integration tests.

## Test Types

### 1. Unit Tests
Located in `tests/Unit/`

#### Service Tests
- `MFLServiceTest`: Tests MFL API integration
- `ECHISServiceTest`: Tests eCHIS API integration
- `SHRServiceTest`: Tests SHR API integration
- `HIEServiceTest`: Tests HIE API integration

#### Model Tests
- `FacilityTest`: Tests facility model relationships and attributes
- `CHUTest`: Tests CHU model relationships and attributes
- `CHWTest`: Tests CHW model relationships and attributes

### 2. Feature Tests
Located in `tests/Feature/`

#### API Tests
- `FacilityControllerTest`: Tests facility API endpoints
- `CHUControllerTest`: Tests CHU API endpoints
- `SyncControllerTest`: Tests sync API endpoints

#### Command Tests
- `ScheduledTasksTest`: Tests scheduled task configuration
- `IntegrationCommandTest`: Tests integration sync commands

### 3. Integration Tests
Located in `tests/Integration/`

- `MFLIntegrationTest`: Tests MFL API integration
- `ECHISIntegrationTest`: Tests eCHIS API integration
- `SHRIntegrationTest`: Tests SHR API integration
- `HIEIntegrationTest`: Tests HIE API integration

## Running Tests

### All Tests
```bash
php artisan test
```

### Specific Test Suite
```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
php artisan test --testsuite=Integration
```

### Specific Test File
```bash
php artisan test tests/Unit/MFLServiceTest.php
```

### Specific Test Method
```bash
php artisan test --filter=test_mfl_sync_schedule
```

## Test Data

### Factories
Located in `database/factories/`

- `FacilityFactory`: Creates test facilities
- `CHUFactory`: Creates test CHUs
- `CHWFactory`: Creates test CHWs

### Seeders
Located in `database/seeders/`

- `TestDataSeeder`: Seeds test data for development
- `IntegrationTestSeeder`: Seeds data for integration tests

## Test Environment

### Configuration
- Uses `.env.testing` for test environment
- Uses SQLite in-memory database for tests
- Mocks external API calls

### Setup
1. Copy `.env.example` to `.env.testing`
2. Configure test database in `.env.testing`
3. Run migrations: `php artisan migrate --env=testing`

## Test Coverage

### Running Coverage Report
```bash
php artisan test --coverage
```

### Coverage Requirements
- Minimum 80% code coverage
- 100% coverage for critical paths
- All public methods must be tested

## Mocking

### API Mocks
```php
$this->mock(MFLService::class, function ($mock) {
    $mock->shouldReceive('sync')
        ->once()
        ->andReturn(['status' => 'success']);
});
```

### Database Mocks
```php
$this->mock(Facility::class, function ($mock) {
    $mock->shouldReceive('find')
        ->with(1)
        ->andReturn(new Facility(['name' => 'Test Facility']));
});
```

## Assertions

### Common Assertions
```php
// Response assertions
$this->assertStatus(200);
$this->assertJsonStructure(['data' => ['id', 'name']]);

// Database assertions
$this->assertDatabaseHas('facilities', ['name' => 'Test Facility']);
$this->assertDatabaseMissing('facilities', ['name' => 'Deleted Facility']);

// Model assertions
$this->assertInstanceOf(Facility::class, $facility);
$this->assertTrue($facility->isActive());
```

## Test Cases

### 1. Facility Management
- Create facility
- Update facility
- Delete facility
- List facilities
- Search facilities
- Filter facilities

### 2. CHU Management
- Create CHU
- Update CHU
- Delete CHU
- List CHUs
- Assign CHWs
- Update CHU status

### 3. Integration Sync
- MFL sync
- eCHIS sync
- SHR sync
- HIE sync
- Error handling
- Retry mechanism

## Continuous Integration

### GitHub Actions
- Runs tests on push
- Runs tests on pull request
- Generates coverage report
- Enforces code style

### Pre-commit Hooks
- Runs tests
- Checks code style
- Validates migrations

## Best Practices

1. **Test Isolation**
   - Each test should be independent
   - Use database transactions
   - Clean up test data

2. **Naming Conventions**
   - Test methods should be descriptive
   - Use `test_` prefix
   - Follow `test_what_it_tests` pattern

3. **Test Data**
   - Use factories for test data
   - Keep test data minimal
   - Use meaningful test data

4. **Assertions**
   - One assertion per test
   - Use specific assertions
   - Test edge cases

5. **Documentation**
   - Document test purpose
   - Document test data
   - Document test setup 