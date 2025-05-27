# Controllers Documentation

## Overview

This document describes the controllers used in the Angaza Referral System. Each controller handles specific functionality and routes.

## AuthController

Handles user authentication and authorization.

```php
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Login successful']);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logout successful']);
    }
}
```

### Methods
- `login()`: Authenticates user and creates session
- `logout()`: Ends user session

## UserController

Manages user accounts and profiles.

```php
class UserController extends Controller
{
    public function index()
    {
        $users = User::with('facility')->paginate(10);
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,facility,user',
            'facility_id' => 'required|exists:facilities,id'
        ]);

        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user->load('facility'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'role' => 'in:admin,facility,user',
            'facility_id' => 'exists:facilities,id'
        ]);

        $user->update($data);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
```

### Methods
- `index()`: List all users
- `store()`: Create new user
- `show()`: Display user details
- `update()`: Update user information
- `destroy()`: Delete user account

## FacilityController

Manages healthcare facilities.

```php
class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::withCount(['users', 'outgoingReferrals', 'incomingReferrals'])
            ->paginate(10);
        return response()->json($facilities);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:facilities',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email'
        ]);

        $facility = Facility::create($data);
        return response()->json($facility, 201);
    }

    public function show(Facility $facility)
    {
        return response()->json($facility->load(['users', 'outgoingReferrals', 'incomingReferrals']));
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $request->validate([
            'name' => 'string|max:255',
            'code' => 'string|unique:facilities,code,' . $facility->id,
            'address' => 'string',
            'phone' => 'string',
            'email' => 'email'
        ]);

        $facility->update($data);
        return response()->json($facility);
    }
}
```

### Methods
- `index()`: List all facilities
- `store()`: Create new facility
- `show()`: Display facility details
- `update()`: Update facility information

## ReferralController

Manages patient referrals.

```php
class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $query = Referral::with(['fromFacility', 'toFacility', 'creator'])
            ->when($request->status, function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->facility_id, function ($q, $facilityId) {
                return $q->where('from_facility_id', $facilityId)
                    ->orWhere('to_facility_id', $facilityId);
            });

        $referrals = $query->paginate(10);
        return response()->json($referrals);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string',
            'from_facility_id' => 'required|exists:facilities,id',
            'to_facility_id' => 'required|exists:facilities,id',
            'notes' => 'nullable|string'
        ]);

        $data['created_by'] = auth()->id();
        $data['status'] = 'pending';

        $referral = Referral::create($data);
        return response()->json($referral, 201);
    }

    public function show(Referral $referral)
    {
        return response()->json($referral->load(['fromFacility', 'toFacility', 'creator', 'logs']));
    }

    public function update(Request $request, Referral $referral)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,accepted,rejected,completed',
            'notes' => 'nullable|string'
        ]);

        $referral->update($data);
        return response()->json($referral);
    }

    public function destroy(Referral $referral)
    {
        $referral->delete();
        return response()->json(null, 204);
    }
}
```

### Methods
- `index()`: List all referrals with filters
- `store()`: Create new referral
- `show()`: Display referral details
- `update()`: Update referral status
- `destroy()`: Delete referral

## Middleware

### Auth Middleware
- Ensures user is authenticated
- Redirects to login if not authenticated

### Admin Middleware
- Ensures user has admin role
- Returns 403 if not admin

### Facility Middleware
- Ensures user belongs to a facility
- Returns 403 if not facility user

### Referral Middleware
- Ensures user has access to the referral
- Returns 403 if unauthorized

## Request Validation

### User Requests
- Name: required, string, max 255
- Email: required, email, unique
- Password: required, min 8
- Role: required, in [admin, facility, user]
- Facility ID: required, exists

### Facility Requests
- Name: required, string, max 255
- Code: required, string, unique
- Address: required, string
- Phone: required, string
- Email: required, email

### Referral Requests
- Patient Name: required, string, max 255
- Patient Phone: required, string
- From Facility ID: required, exists
- To Facility ID: required, exists
- Status: required, in [pending, accepted, rejected, completed]
- Notes: nullable, string 