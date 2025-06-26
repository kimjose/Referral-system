<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    protected $fillable = [
        'mfl_code',
        'name',
        'type',
        'status',
        'county',
        'sub_county',
        'ward'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'mfl_code' => 'required|string|max:50|unique:facilities,mfl_code',
            'type' => 'required|string|max:100',
            'status' => 'required|string|in:Active,Inactive',
            'county' => 'required|string|max:100',
            'sub_county' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100'
        ];
    }

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