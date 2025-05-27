<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityHealthUnit extends Model
{
    protected $fillable = [
        'facility_id',
        'chu_code',
        'name',
        'constituency',
        'ward',
        'village',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function healthWorkers(): HasMany
    {
        return $this->hasMany(CommunityHealthWorker::class, 'chu_id');
    }
} 