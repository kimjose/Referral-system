<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\UserManagementService;
use App\Models\Facility;
use App\Models\Group;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view users')->only(['index', 'show']);
        $this->middleware('permission:create users')->only(['create', 'store']);
        $this->middleware('permission:edit users')->only(['edit', 'update']);
        $this->middleware('permission:delete users')->only(['destroy']);
    }

    /**
     * Display a listing of users with search and filtering
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'userFacility', 'groups']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by facility
        if ($request->filled('facility')) {
            $query->where('facility_id', $request->facility);
        }

        // Filter by group
        if ($request->filled('group')) {
            $query->whereHas('groups', function($q) use ($request) {
                $q->where('group_id', $request->group);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15);
        $roles = Role::all();
        $facilities = Facility::all();
        $groups = Group::all();

        return view('user-management.index', compact('users', 'roles', 'facilities', 'groups'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        $facilities = Facility::all();
        $permissions = Permission::all();
        $groups = Group::all();

        return view('user-management.create', compact('roles', 'facilities', 'permissions', 'groups'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'status' => 'required|in:active,inactive,suspended',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'facility_id' => $request->facility_id,
                'status' => $request->status,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Assign role
            $role = Role::find($request->role_id);
            $user->assignRole($role);

            // Assign groups
            if ($request->has('groups')) {
                $user->groups()->sync($request->groups);
            }

            // Log the action
            Log::info('User created', [
                'user_id' => $user->id,
                'created_by' => Auth::id(),
                'user_data' => $request->except(['password', 'password_confirmation'])
            ]);

            DB::commit();

            return redirect()->route('user-management.index')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User creation failed', [
                'error' => $e->getMessage(),
                'user_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create user. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::with(['roles', 'userFacility', 'permissions'])->findOrFail($id);
        
        // Get user activity log (you can implement this based on your logging system)
        $activityLog = $this->getUserActivityLog($id);

        return view('user-management.show', compact('user', 'activityLog'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::with(['roles', 'permissions', 'groups'])->findOrFail($id);
        $roles = Role::all();
        $facilities = Facility::all();
        $permissions = Permission::all();
        $groups = Group::all();

        return view('user-management.edit', compact('user', 'roles', 'facilities', 'permissions', 'groups'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'role_id' => 'required|exists:roles,id',
            'facility_id' => 'nullable|exists:m_f_l_s,Code',
            'status' => 'required|in:active,inactive,suspended',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldData = $user->toArray();
            
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'role_id' => $request->role_id,
                'facility_id' => $request->facility_id,
                'status' => $request->status,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Update role if changed
            $newRole = Role::find($request->role_id);
            $user->syncRoles([$newRole]);

            // Sync groups
            if ($request->has('groups')) {
                $user->groups()->sync($request->groups);
            } else {
                $user->groups()->detach();
            }

            // Log the action
            Log::info('User updated', [
                'user_id' => $user->id,
                'updated_by' => Auth::id(),
                'old_data' => $oldData,
                'new_data' => $request->all()
            ]);

            DB::commit();

            return redirect()->route('user-management.index')
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User update failed', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'user_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update user. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        try {
            DB::beginTransaction();

            // Log the action before deletion
            Log::info('User deleted', [
                'user_id' => $user->id,
                'deleted_by' => Auth::id(),
                'user_data' => $user->toArray()
            ]);

            $user->delete();

            DB::commit();

            return redirect()->route('user-management.index')
                ->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User deletion failed', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('tab', 'password');
        }

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            Log::info('User password changed', [
                'user_id' => $user->id,
                'changed_by' => Auth::id()
            ]);

            return redirect()->back()
                ->with('success', 'Password changed successfully.')
                ->with('tab', 'password');

        } catch (\Exception $e) {
            Log::error('Password change failed', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to change password. Please try again.')
                ->with('tab', 'password');
        }
    }

    /**
     * Bulk operations on users
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,suspend,delete,change_role',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role_id' => 'required_if:action,change_role|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            DB::beginTransaction();

            $users = User::whereIn('id', $request->user_ids)->get();
            $action = $request->action;

            foreach ($users as $user) {
                switch ($action) {
                    case 'activate':
                        $user->update(['status' => 'active']);
                        break;
                    case 'deactivate':
                        $user->update(['status' => 'inactive']);
                        break;
                    case 'suspend':
                        $user->update(['status' => 'suspended']);
                        break;
                    case 'delete':
                        if ($user->id !== Auth::id()) {
                            $user->delete();
                        }
                        break;
                    case 'change_role':
                        $role = Role::find($request->role_id);
                        $user->syncRoles([$role]);
                        break;
                }
            }

            Log::info('Bulk user action performed', [
                'action' => $action,
                'user_ids' => $request->user_ids,
                'performed_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Bulk action completed successfully']);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk user action failed', [
                'action' => $request->action,
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to perform bulk action']);
        }
    }

    /**
     * Get user statistics
     */
    public function statistics()
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
            'recent_users' => User::latest()->take(5)->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        $query = User::with(['roles', 'userFacility']);

        // Apply filters
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->get();

        // Generate CSV
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Username', 'Role', 'Facility', 'Status', 'Created At']);
            
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
                    $user->created_at
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get user activity log (placeholder - implement based on your logging system)
     */
    private function getUserActivityLog($userId)
    {
        // This is a placeholder. Implement based on your logging system
        // You might want to create a separate UserActivity model and table
        return collect([]);
    }
} 