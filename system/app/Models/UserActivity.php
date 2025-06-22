<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'data',
        'created_by',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get the user that performed the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that created this activity record
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to filter by action type
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted description
     */
    public function getFormattedDescriptionAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->description));
    }

    /**
     * Get activity icon based on action
     */
    public function getActivityIconAttribute()
    {
        $icons = [
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'create' => 'fas fa-plus',
            'update' => 'fas fa-edit',
            'delete' => 'fas fa-trash',
            'view' => 'fas fa-eye',
            'export' => 'fas fa-download',
            'import' => 'fas fa-upload',
            'password_change' => 'fas fa-key',
            'profile_update' => 'fas fa-user-edit',
        ];

        return $icons[$this->action] ?? 'fas fa-info-circle';
    }

    /**
     * Get activity color based on action
     */
    public function getActivityColorAttribute()
    {
        $colors = [
            'login' => 'success',
            'logout' => 'secondary',
            'create' => 'primary',
            'update' => 'warning',
            'delete' => 'danger',
            'view' => 'info',
            'export' => 'success',
            'import' => 'info',
            'password_change' => 'warning',
            'profile_update' => 'primary',
        ];

        return $colors[$this->action] ?? 'secondary';
    }
} 