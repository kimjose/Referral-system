# Integration Tests

## Overview
Integration testing ensures that different components of the system work together correctly and handle data flow between them properly.

## Test Areas

### API Integration
- Endpoint testing
- Request/response validation
- Error handling
- Authentication flows
- Rate limiting

### Database Integration
- Data persistence
- Transaction handling
- Query performance
- Data integrity
- Migration testing

### External Services
- Third-party API integration
- Webhook handling
- Message queue processing
- File system operations
- Cache integration

## Test Implementation

### Test Environment
- Test database setup
- Mock service configuration
- Environment variables
- Test data seeding
- Cleanup procedures

### Test Scenarios
- Happy path testing
- Error path testing
- Edge case handling
- Performance testing
- Security testing

## Example Tests

### API Integration Test
```javascript
describe('Referral API', () => {
  test('creates and retrieves referral', async () => {
    // Create referral
    const createResponse = await api.post('/referrals', {
      patientId: '123',
      facilityId: '456'
    });
    expect(createResponse.status).toBe(201);
    
    // Retrieve referral
    const getResponse = await api.get(`/referrals/${createResponse.data.id}`);
    expect(getResponse.status).toBe(200);
    expect(getResponse.data.patientId).toBe('123');
  });

  test('handles validation errors', async () => {
    const response = await api.post('/referrals', {});
    expect(response.status).toBe(400);
    expect(response.data.errors).toContain('Patient ID is required');
  });
});
```

### Database Integration Test
```javascript
describe('Referral Repository', () => {
  test('persists and retrieves referral', async () => {
    const referral = await repository.create({
      patientId: '123',
      facilityId: '456',
      status: 'pending'
    });
    
    const retrieved = await repository.findById(referral.id);
    expect(retrieved).toMatchObject({
      patientId: '123',
      status: 'pending'
    });
  });

  test('handles concurrent updates', async () => {
    const referral = await repository.create({
      patientId: '123',
      status: 'pending'
    });
    
    await Promise.all([
      repository.updateStatus(referral.id, 'accepted'),
      repository.updateStatus(referral.id, 'rejected')
    ]);
    
    const final = await repository.findById(referral.id);
    expect(['accepted', 'rejected']).toContain(final.status);
  });
}); 