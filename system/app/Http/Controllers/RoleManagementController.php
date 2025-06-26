<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RoleManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view roles')->only(['index', 'show']);
        $this->middleware('permission:create roles')->only(['create', 'store']);
        $this->middleware('permission:edit roles')->only(['edit', 'update']);
        $this->middleware('permission:delete roles')->only(['destroy']);
    }

    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::withCount('users')->with('permissions')->get();
        $permissions = Permission::all();

        return view('role-management.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            if ($parts[0] === 'verify') {
                return 'verification';
            }
            // Group by the resource (e.g., users, roles, patients)
            return $parts[1] ?? 'general';
        });

        return view('role-management.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description,
                'guard_name' => 'web'
            ]);

            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }

            Log::info('Role created', [
                'role_id' => $role->id,
                'created_by' => Auth::id(),
                'role_data' => $request->all()
            ]);

            DB::commit();

            return redirect()->route('role-management.index')
                ->with('success', 'Role created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Role creation failed', [
                'error' => $e->getMessage(),
                'role_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create role. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified role
     */
    public function show($id)
    {
        $role = Role::with(['permissions', 'users'])->findOrFail($id);
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode(' ', $permission->name)[0] ?? 'general';
        });

        return view('role-management.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();

        return view('role-management.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldData = $role->toArray();
            
            $role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $permissions = Permission::whereIn('id', $request->permissions ?? [])->get();
            $role->syncPermissions($permissions);

            Log::info('Role updated', [
                'role_id' => $role->id,
                'updated_by' => Auth::id(),
                'old_data' => $oldData,
                'new_data' => $request->all()
            ]);

            DB::commit();

            return redirect()->route('role-management.index')
                ->with('success', 'Role updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Role update failed', [
                'role_id' => $id,
                'error' => $e->getMessage(),
                'role_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update role. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete role that has assigned users.');
        }

        try {
            DB::beginTransaction();

            Log::info('Role deleted', [
                'role_id' => $role->id,
                'deleted_by' => Auth::id(),
                'role_data' => $role->toArray()
            ]);

            $role->delete();

            DB::commit();

            return redirect()->route('role-management.index')
                ->with('success', 'Role deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Role deletion failed', [
                'role_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to delete role. Please try again.');
        }
    }

    /**
     * Get role statistics
     */
    public function statistics()
    {
        $stats = [
            'total_roles' => Role::count(),
            'roles_with_users' => Role::has('users')->count(),
            'roles_with_permissions' => Role::has('permissions')->count(),
            'users_by_role' => Role::withCount('users')->get(),
            'permissions_by_role' => Role::withCount('permissions')->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Assign permissions to role via AJAX
     */
    public function assignPermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid permissions']);
        }

        try {
            $permissions = Permission::whereIn('id', $request->permissions ?? [])->get();
            $role->syncPermissions($permissions);

            Log::info('Permissions assigned to role', [
                'role_id' => $role->id,
                'assigned_by' => Auth::id(),
                'permissions' => $request->permissions
            ]);

            return response()->json(['success' => true, 'message' => 'Permissions assigned successfully']);

        } catch (\Exception $e) {
            Log::error('Permission assignment failed', [
                'role_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to assign permissions']);
        }
    }

    /**
     * Clone role with permissions
     */
    public function clone(Request $request, $id)
    {
        $originalRole = Role::with('permissions')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $newRole = Role::create([
                'name' => $request->name,
                'description' => $request->description,
                'guard_name' => 'web'
            ]);

            // Clone permissions
            $newRole->syncPermissions($originalRole->permissions);

            Log::info('Role cloned', [
                'original_role_id' => $originalRole->id,
                'new_role_id' => $newRole->id,
                'cloned_by' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('role-management.index')
                ->with('success', 'Role cloned successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Role cloning failed', [
                'original_role_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to clone role. Please try again.')
                ->withInput();
        }
    }
} 