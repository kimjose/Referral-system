# Views Documentation

## Overview

This document describes the views (templates) used in the Angaza Referral System. The views are built using Blade templating engine and include layouts, components, and pages.

## Layouts

### Main Layout (`layouts/app.blade.php`)

The main layout template that provides the basic structure for all pages.

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Angaza Referral System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        @include('partials.navigation')
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer class="footer">
        @include('partials.footer')
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
```

### Auth Layout (`layouts/auth.blade.php`)

Layout for authentication pages (login, register).

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Angaza Referral System</title>
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="auth-container">
        @yield('content')
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
```

## Components

### Navigation (`partials/navigation.blade.php`)

Navigation menu component.

```php
<nav class="navbar">
    <div class="navbar-brand">
        <a href="{{ route('home') }}">Angaza Referral</a>
    </div>

    @auth
        <div class="navbar-menu">
            <a href="{{ route('referrals.index') }}">Referrals</a>
            <a href="{{ route('facilities.index') }}">Facilities</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}">Users</a>
            @endif
        </div>

        <div class="navbar-end">
            <span>{{ auth()->user()->name }}</span>
            <form action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    @endauth
</nav>
```

### Alerts (`components/alerts.blade.php`)

Alert messages component.

```php
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

### Pagination (`components/pagination.blade.php`)

Pagination component.

```php
@if($paginator->hasPages())
    <div class="pagination">
        @if($paginator->onFirstPage())
            <span class="disabled">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">Previous</a>
        @endif

        @foreach($elements as $element)
            @if(is_string($element))
                <span class="disabled">{{ $element }}</span>
            @else
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">Next</a>
        @else
            <span class="disabled">Next</span>
        @endif
    </div>
@endif
```

## Pages

### Login Page (`auth/login.blade.php`)

```php
@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="login-form">
        <h1>Login</h1>
        <form action="{{ route('auth.login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
@endsection
```

### Referrals List (`referrals/index.blade.php`)

```php
@extends('layouts.app')

@section('title', 'Referrals')

@section('content')
    <div class="referrals-page">
        <div class="page-header">
            <h1>Referrals</h1>
            <a href="{{ route('referrals.create') }}" class="btn btn-primary">New Referral</a>
        </div>

        <div class="filters">
            <form action="{{ route('referrals.index') }}" method="GET">
                <select name="status">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                    <option value="completed">Completed</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>

        <div class="referrals-list">
            @foreach($referrals as $referral)
                <div class="referral-card">
                    <h3>{{ $referral->patient_name }}</h3>
                    <p>From: {{ $referral->fromFacility->name }}</p>
                    <p>To: {{ $referral->toFacility->name }}</p>
                    <p>Status: {{ $referral->status }}</p>
                    <a href="{{ route('referrals.show', $referral) }}">View Details</a>
                </div>
            @endforeach
        </div>

        @include('components.pagination', ['paginator' => $referrals])
    </div>
@endsection
```

### Facility Details (`facilities/show.blade.php`)

```php
@extends('layouts.app')

@section('title', $facility->name)

@section('content')
    <div class="facility-details">
        <div class="page-header">
            <h1>{{ $facility->name }}</h1>
            <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-primary">Edit</a>
        </div>

        <div class="facility-info">
            <p><strong>Code:</strong> {{ $facility->code }}</p>
            <p><strong>Address:</strong> {{ $facility->address }}</p>
            <p><strong>Phone:</strong> {{ $facility->phone }}</p>
            <p><strong>Email:</strong> {{ $facility->email }}</p>
        </div>

        <div class="facility-stats">
            <div class="stat-card">
                <h3>Users</h3>
                <p>{{ $facility->users_count }}</p>
            </div>
            <div class="stat-card">
                <h3>Outgoing Referrals</h3>
                <p>{{ $facility->outgoing_referrals_count }}</p>
            </div>
            <div class="stat-card">
                <h3>Incoming Referrals</h3>
                <p>{{ $facility->incoming_referrals_count }}</p>
            </div>
        </div>

        <div class="facility-users">
            <h2>Users</h2>
            <div class="users-list">
                @foreach($facility->users as $user)
                    <div class="user-card">
                        <h4>{{ $user->name }}</h4>
                        <p>{{ $user->email }}</p>
                        <p>Role: {{ $user->role }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
```

## Styling

### Main Styles (`public/css/app.css`)

```css
/* Variables */
:root {
    --primary-color: #4a90e2;
    --secondary-color: #2c3e50;
    --success-color: #2ecc71;
    --error-color: #e74c3c;
    --text-color: #333;
    --light-gray: #f5f6fa;
}

/* Layout */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Navigation */
.navbar {
    background: var(--primary-color);
    color: white;
    padding: 1rem;
}

.navbar-brand a {
    color: white;
    text-decoration: none;
    font-size: 1.5rem;
}

.navbar-menu a {
    color: white;
    text-decoration: none;
    margin-right: 1rem;
}

/* Cards */
.card {
    background: white;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 1rem;
    margin-bottom: 1rem;
}

/* Forms */
.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Buttons */
.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-danger {
    background: var(--error-color);
    color: white;
}

/* Alerts */
.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.alert-success {
    background: var(--success-color);
    color: white;
}

.alert-error {
    background: var(--error-color);
    color: white;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination a,
.pagination span {
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
}

.pagination .active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.pagination .disabled {
    color: #999;
    cursor: not-allowed;
}
```

### Auth Styles (`public/css/auth.css`)

```css
.auth-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light-gray);
}

.auth-container {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

.login-form h1 {
    text-align: center;
    margin-bottom: 2rem;
    color: var(--primary-color);
}

.login-form .form-group {
    margin-bottom: 1.5rem;
}

.login-form label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
}

.login-form input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.login-form button {
    width: 100%;
    padding: 0.75rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
}

.login-form button:hover {
    background: darken(var(--primary-color), 10%);
}
``` 