# Models Documentation

## Overview

This document describes the Eloquent models used in the Angaza Referral System. Each model represents a database table and includes relationships, attributes, and methods.

## User Model

```php
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'facility_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'created_by');
    }

    public function referralLogs()
    {
        return $this->hasMany(ReferralLog::class, 'created_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isFacilityUser()
    {
        return $this->role === 'facility';
    }
}
```

### Relationships
- Belongs to one `Facility`
- Has many `Referrals` (created)
- Has many `ReferralLogs` (created)

### Methods
- `isAdmin()`: Checks if user has admin role
- `isFacilityUser()`: Checks if user has facility role

## Facility Model

```php
class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function outgoingReferrals()
    {
        return $this->hasMany(Referral::class, 'from_facility_id');
    }

    public function incomingReferrals()
    {
        return $this->hasMany(Referral::class, 'to_facility_id');
    }
}
```

### Relationships
- Has many `Users`
- Has many `Referrals` (outgoing)
- Has many `Referrals` (incoming)

## Referral Model

```php
class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'patient_phone',
        'from_facility_id',
        'to_facility_id',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function fromFacility()
    {
        return $this->belongsTo(Facility::class, 'from_facility_id');
    }

    public function toFacility()
    {
        return $this->belongsTo(Facility::class, 'to_facility_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(ReferralLog::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
```

### Relationships
- Belongs to `Facility` (from)
- Belongs to `Facility` (to)
- Belongs to `User` (creator)
- Has many `ReferralLogs`

### Scopes
- `pending()`: Filter pending referrals
- `accepted()`: Filter accepted referrals
- `rejected()`: Filter rejected referrals
- `completed()`: Filter completed referrals

## ReferralLog Model

```php
class ReferralLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_id',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

### Relationships
- Belongs to `Referral`
- Belongs to `User` (creator)

## Model Events

### User Model Events
- `created`: Hash password before saving
- `updated`: Hash password if changed
- `deleting`: Prevent deletion if has referrals

### Referral Model Events
- `created`: Create initial log entry
- `updated`: Create log entry for status changes
- `deleting`: Delete associated logs

### Facility Model Events
- `deleting`: Prevent deletion if has users or referrals

## Model Observers

### UserObserver
- Handles password hashing
- Manages user deletion restrictions

### ReferralObserver
- Manages referral status changes
- Creates log entries
- Handles referral deletion

### FacilityObserver
- Manages facility deletion restrictions
- Updates related records 