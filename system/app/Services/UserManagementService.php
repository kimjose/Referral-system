<?php

namespace App\Services;

use App\Models\User;
use App\Models\m_f_l_s;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserManagementService
{
    /**
     * Create a new user with role assignment
     */
    public function createUser(array $data)
    {
        try {
            DB::beginTransaction();

            // Generate username if not provided
            if (empty($data['username'])) {
                $data['username'] = $this->generateUsername($data['name'], $data['email']);
            }

            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role_id' => $data['role_id'],
                'facility_id' => $data['facility_id'] ?? null,
                'status' => $data['status'] ?? 'active',
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Assign role
            if (isset($data['role_id'])) {
                $role = Role::find($data['role_id']);
                if ($role) {
                    $user->assignRole($role);
                }
            }

            // Log activity
            $user->logActivity('create', 'user created', [
                'created_by' => Auth::id(),
                'user_data' => $data
            ]);

            DB::commit();

            return $user;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Update user information
     */
    public function updateUser(User $user, array $data)
    {
        try {
            DB::beginTransaction();

            $oldData = $user->toArray();

            // Update user
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'role_id' => $data['role_id'],
                'facility_id' => $data['facility_id'] ?? null,
                'status' => $data['status'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            // Update role if changed
            if (isset($data['role_id']) && $data['role_id'] != $oldData['role_id']) {
                $newRole = Role::find($data['role_id']);
                if ($newRole) {
                    $user->syncRoles([$newRole]);
                }
            }

            // Log activity
            $user->logActivity('update', 'user updated', [
                'updated_by' => Auth::id(),
                'old_data' => $oldData,
                'new_data' => $data
            ]);

            DB::commit();

            return $user;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        try {
            DB::beginTransaction();

            // Prevent self-deletion
            if ($user->id === Auth::id()) {
                throw new \Exception('You cannot delete your own account.');
            }

            // Log activity before deletion
            $user->logActivity('delete', 'user deleted', [
                'deleted_by' => Auth::id(),
                'user_data' => $user->toArray()
            ]);

            $user->delete();

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User deletion failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Change user password
     */
    public function changePassword(User $user, string $newPassword)
    {
        try {
            $user->update([
                'password' => Hash::make($newPassword),
                'force_password_change' => false,
                'updated_by' => Auth::id(),
            ]);

            $user->logActivity('password_change', 'password changed', [
                'changed_by' => Auth::id()
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Password change failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Bulk operations on users
     */
    public function bulkAction(array $userIds, string $action, array $options = [])
    {
        try {
            DB::beginTransaction();

            $users = User::whereIn('id', $userIds)->get();
            $results = [];

            foreach ($users as $user) {
                switch ($action) {
                    case 'activate':
                        $user->update(['status' => 'active']);
                        $results[] = "User {$user->name} activated";
                        break;

                    case 'deactivate':
                        $user->update(['status' => 'inactive']);
                        $results[] = "User {$user->name} deactivated";
                        break;

                    case 'suspend':
                        $user->update(['status' => 'suspended']);
                        $results[] = "User {$user->name} suspended";
                        break;

                    case 'delete':
                        if ($user->id !== Auth::id()) {
                            $this->deleteUser($user);
                            $results[] = "User {$user->name} deleted";
                        } else {
                            $results[] = "Cannot delete your own account";
                        }
                        break;

                    case 'change_role':
                        if (isset($options['role_id'])) {
                            $role = Role::find($options['role_id']);
                            if ($role) {
                                $user->syncRoles([$role]);
                                $results[] = "User {$user->name} role changed to {$role->name}";
                            }
                        }
                        break;

                    case 'force_password_change':
                        $user->forcePasswordChange();
                        $results[] = "User {$user->name} forced to change password";
                        break;
                }
            }

            // Log bulk action
            Auth::user()->logActivity('bulk_action', "bulk {$action} performed", [
                'action' => $action,
                'user_ids' => $userIds,
                'options' => $options,
                'results' => $results
            ]);

            DB::commit();

            return $results;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk action failed', [
                'action' => $action,
                'user_ids' => $userIds,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get user statistics
     */
    public function getUserStatistics()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'suspended_users' => User::where('status', 'suspended')->count(),
            'users_by_role' => User::with('roles')
                ->get()
                ->groupBy(function($user) {
                    return $user->roles->first()->name ?? 'No Role';
                })
                ->map->count(),
            'users_by_facility' => User::with('userFacility')
                ->get()
                ->groupBy(function($user) {
                    return $user->userFacility->Officialname ?? 'No Facility';
                })
                ->map->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'users_created_today' => User::whereDate('created_at', today())->count(),
            'users_created_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'users_created_this_year' => User::whereYear('created_at', now()->year)->count(),
        ];

        return $stats;
    }

    /**
     * Search users with filters
     */
    public function searchUsers(array $filters = [])
    {
        $query = User::with(['roles', 'userFacility']);

        // Search by name, email, or username
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if (isset($filters['role'])) {
            $query->whereHas('roles', function($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        // Filter by facility
        if (isset($filters['facility'])) {
            $query->where('facility_id', $filters['facility']);
        }

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by date range
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $filters['per_page'] ?? 15;
        
        return $query->paginate($perPage);
    }

    /**
     * Generate unique username
     */
    private function generateUsername(string $name, string $email)
    {
        $baseUsername = Str::slug(explode(' ', $name)[0]);
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Import users from CSV
     */
    public function importUsersFromCsv($file)
    {
        try {
            DB::beginTransaction();

            $csvData = array_map('str_getcsv', file($file->getPathname()));
            $headers = array_shift($csvData);
            $imported = 0;
            $errors = [];

            foreach ($csvData as $row) {
                $data = array_combine($headers, $row);
                
                try {
                    $this->createUser($data);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($imported + 1) . ": " . $e->getMessage();
                }
            }

            // Log import activity
            Auth::user()->logActivity('import', 'users imported from CSV', [
                'imported_count' => $imported,
                'errors' => $errors
            ]);

            DB::commit();

            return [
                'imported' => $imported,
                'errors' => $errors
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User import failed', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Export users to CSV
     */
    public function exportUsers(array $filters = [])
    {
        $users = $this->searchUsers($filters)->items();

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Username', 'Role', 'Facility', 'Status', 'Phone', 'Address', 'Created At']);
            
            // CSV data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->username,
                    $user->roles->first()->name ?? 'No Role',
                    $user->userFacility->Officialname ?? 'No Facility',
                    $user->status,
                    $user->phone,
                    $user->address,
                    $user->created_at
                ]);
            }
            
            fclose($file);
        };

        // Log export activity
        Auth::user()->logActivity('export', 'users exported to CSV', [
            'filters' => $filters,
            'count' => count($users)
        ]);

        return response()->stream($callback, 200, $headers);
    }
} 