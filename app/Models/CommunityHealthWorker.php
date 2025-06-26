<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityHealthWorker extends Model
{
    protected $fillable = [
        'chu_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'role',
        'date_of_birth',
        'gender',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    public function communityHealthUnit(): BelongsTo
    {
        return $this->belongsTo(CommunityHealthUnit::class, 'chu_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
} 