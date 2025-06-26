<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;
use App\Models\m_f_l_s;
use App\Models\Group;
use Spatie\Permission\Contracts\Permission as PermissionContract;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role_id',
        'facility_id',
        'status',
        'phone',
        'address',
        'profile_picture',
        'bio',
        'last_login_at',
        'last_login_ip',
        'force_password_change',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'force_password_change' => 'boolean',
    ];

    /**
     * Get the user's role
     */
    public function userRole(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    /**
     * Get the user's facility
     */
    public function userFacility()
    {
        return $this->belongsTo(m_f_l_s::class, 'facility_id');
    }

    /**
     * Get user activities
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    /**
     * Get user who created this user
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get user who last updated this user
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if user is inactive
     */
    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get user's display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->name ?: $this->username;
    }

    /**
     * Get user's role name
     */
    public function getRoleNameAttribute()
    {
        return $this->roles->first()->name ?? 'No Role';
    }

    /**
     * Get user's facility name
     */
    public function getFacilityNameAttribute()
    {
        return $this->userFacility->Officialname ?? 'No Facility';
    }

    /**
     * Update last login information
     */
    public function updateLastLogin($ipAddress = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress ?: request()->ip(),
        ]);
    }

    /**
     * Force password change
     */
    public function forcePasswordChange()
    {
        $this->update(['force_password_change' => true]);
    }

    /**
     * Clear force password change flag
     */
    public function clearForcePasswordChange()
    {
        $this->update(['force_password_change' => false]);
    }

    /**
     * Check if user needs to change password
     */
    public function needsPasswordChange()
    {
        return $this->force_password_change;
    }

    /**
     * Get user's recent activities
     */
    public function getRecentActivities($limit = 10)
    {
        return $this->activities()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Log user activity
     */
    public function logActivity($action, $description, $data = null)
    {
        return $this->activities()->create([
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => $data,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for suspended users
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Scope for users by role
     */
    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('roles', function($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    /**
     * Scope for users by facility
     */
    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    /**
     * Boot method to automatically set created_by and updated_by
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (Auth::check()) {
                $user->created_by = Auth::id();
            }
        });

        static::updating(function ($user) {
            if (Auth::check()) {
                $user->updated_by = Auth::id();
            }
        });
    }

    /**
     * The groups that the user belongs to.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role, $guard = null)
    {
        return $this->roles->contains('name', $role) || $this->roles->contains('id', $role);
    }

    /**
     * Override the hasPermissionViaRole method to include permissions from groups.
     *
     * @param \Spatie\Permission\Contracts\Permission $permission
     * @return bool
     */
    protected function hasPermissionViaRole(PermissionContract $permission): bool
    {
        // Check for permissions via direct roles
        if ($this->roles->contains(fn($role) => $role->hasPermissionTo($permission))) {
            return true;
        }

        // Check for permissions via group roles
        return $this->groups->loadMissing('roles.permissions')
            ->pluck('roles')
            ->flatten()
            ->contains(fn($role) => $role->hasPermissionTo($permission));
    }
}
