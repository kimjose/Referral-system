<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add permission middleware once permissions are set up
    }

    public function index()
    {
        $groups = Group::withCount('users')->latest()->paginate(10);
        return view('user-management.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('user-management.groups.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Group::create($request->all());

        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    public function edit(Group $group)
    {
        $roles = Role::all();
        $group->load('roles');
        return view('user-management.groups.edit', compact('group', 'roles'));
    }

    public function update(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id,
            'description' => 'nullable|string',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $group->update($request->only('name', 'description'));

        if ($request->has('roles')) {
            $group->roles()->sync($request->roles);
        } else {
            $group->roles()->detach();
        }

        return redirect()->route('groups.index')->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }
} 