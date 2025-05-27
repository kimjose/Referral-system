<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(FacilityContact::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(FacilityAddress::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(FacilityService::class);
    }

    public function communityHealthUnits(): HasMany
    {
        return $this->hasMany(CommunityHealthUnit::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }
} 