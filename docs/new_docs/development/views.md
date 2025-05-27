# Views Documentation

## Overview

This document describes the Blade views used in the Angaza Referral System. Views are organized by functionality and follow Laravel's Blade templating system.

## Layout Structure

### Main Layout

```php
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Angaza Referral System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav>
        @include('layouts.navigation')
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        @include('layouts.footer')
    </footer>
</body>
</html>
```

### Navigation

```php
<nav class="navbar">
    <div class="container">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        
        @auth
            <div class="nav-links">
                <a href="{{ route('referrals.index') }}">Referrals</a>
                <a href="{{ route('facilities.index') }}">Facilities</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
            </div>
        @endauth
    </div>
</nav>
```

## View Components

### Referral Forms

#### Tab 1: Patient Information

```php
<form action="{{ route('referral.tabs.save', ['tab' => 1]) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="patient_name">Patient Name</label>
        <input type="text" name="patient_name" id="patient_name" required>
    </div>

    <div class="form-group">
        <label for="patient_phone">Phone Number</label>
        <input type="tel" name="patient_phone" id="patient_phone" required>
    </div>

    <button type="submit">Next</button>
</form>
```

#### Tab 2: Facility Selection

```php
<form action="{{ route('referral.tabs.save', ['tab' => 2]) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="to_facility_id">Receiving Facility</label>
        <select name="to_facility_id" id="to_facility_id" required>
            @foreach($facilities as $facility)
                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit">Next</button>
</form>
```

### Dashboard Views

#### Admin Dashboard

```php
<div class="dashboard">
    <div class="stats">
        <div class="stat-card">
            <h3>Total Referrals</h3>
            <p>{{ $totalReferrals }}</p>
        </div>
        <div class="stat-card">
            <h3>Active Facilities</h3>
            <p>{{ $activeFacilities }}</p>
        </div>
    </div>

    <div class="recent-activity">
        <h2>Recent Activity</h2>
        @foreach($recentReferrals as $referral)
            <div class="activity-item">
                <p>{{ $referral->patient_name }}</p>
                <span>{{ $referral->created_at->diffForHumans() }}</span>
            </div>
        @endforeach
    </div>
</div>
```

#### Facility Dashboard

```php
<div class="dashboard">
    <div class="referral-stats">
        <div class="stat-card">
            <h3>Outgoing Referrals</h3>
            <p>{{ $outgoingReferrals }}</p>
        </div>
        <div class="stat-card">
            <h3>Incoming Referrals</h3>
            <p>{{ $incomingReferrals }}</p>
        </div>
    </div>

    <div class="referral-list">
        <h2>Recent Referrals</h2>
        @foreach($referrals as $referral)
            <div class="referral-item">
                <p>{{ $referral->patient_name }}</p>
                <span>{{ $referral->status }}</span>
            </div>
        @endforeach
    </div>
</div>
```

## View Components

### Alerts

```php
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
```

### Forms

```php
<form action="{{ $action }}" method="POST">
    @csrf
    @method($method)

    <div class="form-group">
        <label for="{{ $field }}">{{ $label }}</label>
        <input type="{{ $type }}" 
               name="{{ $field }}" 
               id="{{ $field }}" 
               value="{{ old($field, $value) }}"
               @if($required) required @endif>
        @error($field)
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit">{{ $submitText }}</button>
</form>
```

## Best Practices

1. **Component Reusability**
   - Create reusable components
   - Use includes for common elements
   - Follow DRY principle

2. **Form Handling**
   - Always include CSRF token
   - Validate on both client and server
   - Show validation errors

3. **Security**
   - Escape output using {{ }}
   - Use @csrf for forms
   - Validate all inputs

4. **Performance**
   - Cache when possible
   - Lazy load components
   - Optimize assets

## Asset Management

### CSS

```php
@vite(['resources/css/app.css'])
```

### JavaScript

```php
@vite(['resources/js/app.js'])
```

### Images

```php
<img src="{{ asset('images/logo.png') }}" alt="Logo">
```

## Error Handling

### 404 Page

```php
@extends('layouts.error')

@section('content')
    <div class="error-page">
        <h1>404</h1>
        <p>Page not found</p>
        <a href="{{ route('home') }}">Return Home</a>
    </div>
@endsection
```

### 500 Page

```php
@extends('layouts.error')

@section('content')
    <div class="error-page">
        <h1>500</h1>
        <p>Server error</p>
        <a href="{{ route('home') }}">Return Home</a>
    </div>
@endsection
``` 