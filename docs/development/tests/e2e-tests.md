# End-to-End Tests

## Overview
End-to-end testing validates the complete user workflow and ensures all system components work together as expected in a production-like environment.

## Test Scenarios

### User Workflows
- User registration and login
- Referral creation and management
- Patient record management
- Facility management
- Report generation

### Critical Paths
- Complete referral lifecycle
- Emergency referral handling
- Multi-facility workflows
- Data synchronization
- Error recovery

## Test Implementation

### Cypress Configuration
- Environment setup
- Test data management
- Custom commands
- Test isolation
- Reporting configuration

### Test Structure
- Page objects
- Test utilities
- Custom assertions
- Test data factories
- Cleanup procedures

## Example Tests

### User Workflow Test
```javascript
describe('Referral Workflow', () => {
  beforeEach(() => {
    cy.login('doctor@hospital.com', 'password');
  });

  it('completes referral process', () => {
    // Create referral
    cy.visit('/referrals/new');
    cy.get('[data-test="patient-name"]').type('John Doe');
    cy.get('[data-test="facility"]').select('City Hospital');
    cy.get('[data-test="submit"]').click();
    
    // Verify creation
    cy.url().should('include', '/referrals');
    cy.get('[data-test="referral-status"]').should('contain', 'Pending');
    
    // Accept referral
    cy.get('[data-test="accept-referral"]').click();
    cy.get('[data-test="referral-status"]').should('contain', 'Accepted');
  });

  it('handles validation errors', () => {
    cy.visit('/referrals/new');
    cy.get('[data-test="submit"]').click();
    cy.get('[data-test="error-message"]')
      .should('contain', 'Patient name is required');
  });
});
```

### Critical Path Test
```javascript
describe('Emergency Referral', () => {
  it('handles emergency referral workflow', () => {
    // Create emergency referral
    cy.visit('/referrals/new');
    cy.get('[data-test="emergency-checkbox"]').check();
    cy.get('[data-test="patient-name"]').type('Jane Smith');
    cy.get('[data-test="facility"]').select('Emergency Center');
    cy.get('[data-test="submit"]').click();
    
    // Verify emergency handling
    cy.get('[data-test="priority-badge"]').should('contain', 'Emergency');
    cy.get('[data-test="notification"]').should('contain', 'Emergency referral created');
    
    // Complete emergency workflow
    cy.get('[data-test="accept-referral"]').click();
    cy.get('[data-test="referral-status"]').should('contain', 'In Progress');
  });
});
``` 