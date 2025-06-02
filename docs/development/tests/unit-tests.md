# Unit Tests

## Overview
Unit testing is a fundamental part of our testing strategy, focusing on testing individual components and functions in isolation.

## Test Structure

### Component Tests
- React component testing
- Component rendering
- User interactions
- State management
- Props validation

### Service Tests
- Business logic testing
- Data processing
- Error handling
- Service integration
- Mock implementations

### Utility Tests
- Helper functions
- Data transformations
- Validation logic
- Formatting utilities
- Common operations

## Test Implementation

### Jest Configuration
- Test environment setup
- Mock configurations
- Coverage settings
- Test timeouts
- Global setup

### Test Cases
- Input validation
- Edge cases
- Error scenarios
- Success paths
- Performance checks

## Best Practices

### Test Organization
- Clear test structure
- Meaningful descriptions
- Proper isolation
- Maintainable code
- Documentation

### Test Coverage
- Minimum coverage requirements
- Critical path coverage
- Edge case coverage
- Error handling coverage
- Integration points

## Example Tests

### Component Test
```javascript
describe('ReferralForm', () => {
  test('renders form fields correctly', () => {
    const { getByLabelText } = render(<ReferralForm />);
    expect(getByLabelText('Patient Name')).toBeInTheDocument();
    expect(getByLabelText('Facility')).toBeInTheDocument();
  });

  test('validates required fields', async () => {
    const { getByText } = render(<ReferralForm />);
    fireEvent.click(getByText('Submit'));
    expect(await screen.findByText('Patient Name is required')).toBeInTheDocument();
  });
});
```

### Service Test
```javascript
describe('ReferralService', () => {
  test('creates new referral', async () => {
    const mockData = {
      patientId: '123',
      facilityId: '456'
    };
    
    const result = await referralService.create(mockData);
    expect(result).toHaveProperty('id');
    expect(result.status).toBe('pending');
  });

  test('handles validation errors', async () => {
    await expect(referralService.create({}))
      .rejects
      .toThrow('Invalid referral data');
  });
});
``` 