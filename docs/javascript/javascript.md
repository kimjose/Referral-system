# JavaScript Documentation

## Overview

This document describes the JavaScript functionality used in the Angaza Referral System. The system uses vanilla JavaScript for client-side interactions and form handling.

## Main Application Script (`public/js/app.js`)

```javascript
// Main application functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeNavigation();
    initializeForms();
    initializeAlerts();
});

// Navigation functionality
function initializeNavigation() {
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const navbarMenu = document.querySelector('.navbar-menu');

    if (mobileMenuButton && navbarMenu) {
        mobileMenuButton.addEventListener('click', () => {
            navbarMenu.classList.toggle('is-active');
        });
    }
}

// Form handling
function initializeForms() {
    const forms = document.querySelectorAll('form[data-ajax="true"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
}

// Form submission handler
async function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitButton = form.querySelector('[type="submit"]');
    const originalButtonText = submitButton.textContent;
    
    try {
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';
        
        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showAlert('success', data.message || 'Operation successful');
            if (form.dataset.redirect) {
                window.location.href = form.dataset.redirect;
            }
        } else {
            showAlert('error', data.message || 'An error occurred');
        }
    } catch (error) {
        showAlert('error', 'An error occurred while processing your request');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = originalButtonText;
    }
}

// Alert handling
function initializeAlerts() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade-out');
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
}

// Show alert message
function showAlert(type, message) {
    const alertContainer = document.querySelector('.alert-container') || createAlertContainer();
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    alertContainer.appendChild(alert);
    
    setTimeout(() => {
        alert.classList.add('fade-out');
        setTimeout(() => alert.remove(), 300);
    }, 5000);
}

// Create alert container if it doesn't exist
function createAlertContainer() {
    const container = document.createElement('div');
    container.className = 'alert-container';
    document.body.appendChild(container);
    return container;
}
```

## Authentication Script (`public/js/auth.js`)

```javascript
// Authentication page functionality
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
});

// Login form handler
async function handleLogin(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitButton = form.querySelector('[type="submit"]');
    const originalButtonText = submitButton.textContent;
    
    try {
        submitButton.disabled = true;
        submitButton.textContent = 'Logging in...';
        
        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            window.location.href = data.redirect || '/dashboard';
        } else {
            showAlert('error', data.message || 'Invalid credentials');
        }
    } catch (error) {
        showAlert('error', 'An error occurred while logging in');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = originalButtonText;
    }
}
```

## Referral Management Script (`public/js/referrals.js`)

```javascript
// Referral management functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeReferralFilters();
    initializeReferralStatusUpdates();
});

// Initialize referral filters
function initializeReferralFilters() {
    const filterForm = document.querySelector('.referral-filters');
    
    if (filterForm) {
        const filterInputs = filterForm.querySelectorAll('select, input');
        
        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                filterForm.submit();
            });
        });
    }
}

// Initialize referral status updates
function initializeReferralStatusUpdates() {
    const statusForms = document.querySelectorAll('.referral-status-form');
    
    statusForms.forEach(form => {
        form.addEventListener('submit', handleStatusUpdate);
    });
}

// Handle status update
async function handleStatusUpdate(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitButton = form.querySelector('[type="submit"]');
    const originalButtonText = submitButton.textContent;
    
    try {
        submitButton.disabled = true;
        submitButton.textContent = 'Updating...';
        
        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'PUT',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showAlert('success', data.message || 'Status updated successfully');
            updateReferralStatus(data.referral);
        } else {
            showAlert('error', data.message || 'Failed to update status');
        }
    } catch (error) {
        showAlert('error', 'An error occurred while updating status');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = originalButtonText;
    }
}

// Update referral status in UI
function updateReferralStatus(referral) {
    const statusElement = document.querySelector(`#referral-${referral.id} .status`);
    if (statusElement) {
        statusElement.textContent = referral.status;
        statusElement.className = `status status-${referral.status}`;
    }
}
```

## Facility Management Script (`public/js/facilities.js`)

```javascript
// Facility management functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeFacilitySearch();
    initializeFacilityStats();
});

// Initialize facility search
function initializeFacilitySearch() {
    const searchInput = document.querySelector('.facility-search');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleFacilitySearch, 300));
    }
}

// Handle facility search
async function handleFacilitySearch(event) {
    const searchTerm = event.target.value;
    
    try {
        const response = await fetch(`/api/facilities/search?q=${encodeURIComponent(searchTerm)}`);
        const data = await response.json();
        
        if (response.ok) {
            updateFacilityList(data.facilities);
        }
    } catch (error) {
        console.error('Error searching facilities:', error);
    }
}

// Update facility list in UI
function updateFacilityList(facilities) {
    const facilityList = document.querySelector('.facility-list');
    
    if (facilityList) {
        facilityList.innerHTML = facilities.map(facility => `
            <div class="facility-card">
                <h3>${facility.name}</h3>
                <p>${facility.address}</p>
                <p>Phone: ${facility.phone}</p>
                <a href="/facilities/${facility.id}" class="btn btn-primary">View Details</a>
            </div>
        `).join('');
    }
}

// Initialize facility statistics
function initializeFacilityStats() {
    const statsContainer = document.querySelector('.facility-stats');
    
    if (statsContainer) {
        updateFacilityStats();
    }
}

// Update facility statistics
async function updateFacilityStats() {
    try {
        const response = await fetch('/api/facilities/stats');
        const data = await response.json();
        
        if (response.ok) {
            updateStatsUI(data.stats);
        }
    } catch (error) {
        console.error('Error fetching facility stats:', error);
    }
}

// Update statistics in UI
function updateStatsUI(stats) {
    Object.entries(stats).forEach(([key, value]) => {
        const element = document.querySelector(`.stat-${key}`);
        if (element) {
            element.textContent = value;
        }
    });
}

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
```

## Event Handlers

### Form Submission
- Handles AJAX form submissions
- Shows loading states
- Displays success/error messages
- Handles redirects

### Status Updates
- Updates referral statuses
- Refreshes UI elements
- Shows confirmation messages

### Search Functionality
- Debounced search input
- Real-time results
- Error handling

### Alert System
- Shows temporary messages
- Auto-dismisses after timeout
- Supports multiple types (success, error)

## Utility Functions

### Form Handling
- `handleFormSubmit()`: Processes form submissions
- `showAlert()`: Displays alert messages
- `createAlertContainer()`: Creates alert container

### Data Fetching
- `fetchData()`: Generic data fetching
- `handleResponse()`: Processes API responses
- `handleError()`: Error handling

### UI Updates
- `updateUI()`: Updates UI elements
- `showLoading()`: Shows loading state
- `hideLoading()`: Hides loading state

## Error Handling

### Network Errors
- Handles failed requests
- Shows user-friendly messages
- Retries on failure

### Validation Errors
- Displays form errors
- Highlights invalid fields
- Clears errors on input

### API Errors
- Processes error responses
- Shows appropriate messages
- Handles different status codes 