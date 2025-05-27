# Routes Documentation

## Overview

This document describes the routes used in the Angaza Referral System. The routes are organized by functionality and access level.

## Authentication Routes

```php
Route::get('/', [UserController::class, 'signIn'])->name('user.signIn');
Route::post('/user-login', [UserController::class, 'login'])->name('user.login');
```

## Facility Routes

```php
Route::get('/facility/set', [UserController::class, 'getFacilities'])->name('facility.set');
Route::post('/facility/select', [UserController::class, 'select'])->name('facility.select');
```

## Dashboard Routes

```php
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
Route::get('/admin', [UserController::class, 'admin'])->name('admin.dashboard');
Route::get('/doctor', [UserController::class, 'doctor'])->name('doctor.dashboard');
```

## Statistics Routes

```php
Route::get('/patients-count', [UserController::class, 'getPatientsCount'])->name('patients.count');
Route::get('/physicians-count', [UserController::class, 'getPhysiciansCount'])->name('physicians.count');
Route::get('/referrals-count', [UserController::class, 'getReferralsCount'])->name('referrals.count');
```

## Triage Routes

```php
Route::get('/triages', [TriageController::class, 'addTriage'])->name('triages.addTriage');
Route::post('/store-form', [TriageController::class, 'store'])->name('triages.store-form');
```

## Assessment Routes

```php
// PHQ9 Assessment
Route::get('phq9', [Phq9Controller::class, 'addAssessment'])->name('phq9.addAssessment');
Route::post('phq9/store', [Phq9Controller::class, 'storeAssessment'])->name('phq9.storeAssessment');

// GAD7 Assessment
Route::get('gad7', [ExtraFormsController::class, 'addGad7'])->name('gad7.addGad7');
Route::post('gad7/store', [ExtraFormsController::class, 'storeGad7'])->name('gad7.storeGad7');

// PTSD5 Assessment
Route::get('ptsd5', [ExtraFormsController::class, 'addPtsd5'])->name('ptsd5.addPtsd5');
Route::post('ptsd5/store', [ExtraFormsController::class, 'storePtsd5'])->name('ptsd5.storePtsd5');
```

## Admin Routes

```php
Route::get('/admin/dashboard/charts', [AdminController::class, 'admin'])->name('admin.dashboard.charts');
Route::get('/admin/test-charts', [AdminController::class, 'testCharts'])->name('admin.test-charts');
```

## Referral Routes

```php
// Testing routes
Route::post('/testing', [ReferralController::class, 'sendtesting'])->name('sendreferral');
Route::get('/service_category/get_services', [MflController::class, 'getServiceFromCategory'])->name('services_from_service_category');
Route::get('/referralprocess', [ReferralController::class, 'outgoingReferralTabs']);

// Referral tabs
Route::get('referral/tabs/{tab}', [ReferralController::class, 'show'])->name('referral.tabs');
Route::post('referral/tabs/save/{tab}', [ReferralController::class, 'saveTabData'])->name('referral.tabs.save');
```

## API Routes

```php
// Referral API Routes
Route::post('/referral/save/tab1', [ReferralTabController::class, 'saveTab1Data']);
Route::post('/referral/save/tab2', [ReferralTabController::class, 'saveTab2Data']);
Route::post('/referral/save/tab3', [ReferralTabController::class, 'saveTab3Data']);
Route::post('/referral/api-referral', [ReferralTabController::class, 'apiReferral']);

// SMS Routes
Route::post('/sms/referral_sms', [SmsController::class, 'sendSms']);

// MFL API Routes
Route::get('/mfl/service_categories', [MflController::class, 'getServiceCategories']);
Route::get('/mfl/facility_types', [MflController::class, 'getFacilityTypes']);
Route::get('/mfl/counties', [MflController::class, 'getCounties']);
Route::get('/mfl/facilities/by_service', [MflController::class, 'getFacilityFromService']);
Route::get('/mfl/services/by_category', [MflController::class, 'getServiceFromCategory']);
```

## Route Middleware

The following middleware is applied to routes:

- `auth`: Ensures user is authenticated
- `admin`: Ensures user has admin role
- `facility`: Ensures user belongs to a facility
- `referral`: Ensures user has access to the referral

## Route Groups

Routes are grouped by functionality and access level:

1. Public Routes
   - Authentication
   - Public information

2. Authenticated Routes
   - Dashboard
   - User profile
   - Facility management

3. Admin Routes
   - System configuration
   - User management
   - Reports

4. API Routes
   - Referral management
   - SMS notifications
   - MFL integration 