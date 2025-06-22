# Dynamic User Management Module

## Overview

The Dynamic User Management Module is a comprehensive solution for managing users, roles, and permissions in the Angaza Referral System. It provides a complete set of features for user administration, role-based access control, and audit trail functionality.

## Features

### Core Features

1. **User Management**
   - Create, read, update, and delete users
   - User search and filtering
   - Bulk user operations
   - User status management (active, inactive, suspended)
   - Password management
   - User profile management

2. **Role Management**
   - Create and manage roles
   - Assign permissions to roles
   - Role cloning functionality
   - Role-based access control

3. **Permission Management**
   - Granular permission system
   - Permission assignment to roles
   - Permission-based middleware

4. **Audit Trail**
   - User activity logging
   - Activity tracking middleware
   - Comprehensive audit reports

5. **Advanced Features**
   - User import/export functionality
   - User statistics and analytics
   - Force password change
   - Last login tracking
   - User activity monitoring

## Architecture

### Models

#### User Model (`app/Models/User.php`)
- Extends Laravel's Authenticatable
- Uses Spatie Permission traits
- Includes additional fields for enhanced user management
- Provides activity logging methods
- Includes scopes for filtering

#### UserActivity Model (`app/Models/UserActivity.php`)
- Tracks user activities and audit trail
- Stores action types, descriptions, and metadata
- Provides formatted display methods

### Controllers

#### UserManagementController (`app/Http/Controllers/UserManagementController.php`)
- Handles user CRUD operations
- Manages user search and filtering
- Provides bulk operations
- Handles password changes
- Manages user exports

#### RoleManagementController (`app/Http/Controllers/RoleManagementController.php`)
- Manages role CRUD operations
- Handles permission assignments
- Provides role cloning functionality
- Manages role statistics

### Services

#### UserManagementService (`app/Services/UserManagementService.php`)
- Contains business logic for user operations
- Handles complex user management tasks
- Provides import/export functionality
- Manages user statistics

### Middleware

#### TrackUserActivity (`app/Http/Middleware/TrackUserActivity.php`)
- Automatically tracks user activities
- Logs actions based on HTTP methods and routes
- Provides comprehensive audit trail

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id BIGINT UNSIGNED,
    facility_id VARCHAR(255),
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    phone VARCHAR(20),
    address TEXT,
    profile_picture VARCHAR(255),
    bio TEXT,
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45),
    force_password_change BOOLEAN DEFAULT FALSE,
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_last_login (last_login_at)
);
```

### User Activities Table
```sql
CREATE TABLE user_activities (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED,
    action VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    data JSON,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_created (user_id, created_at),
    INDEX idx_action_created (action, created_at)
);
```

## Installation and Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Permissions and Roles
```bash
php artisan db:seed --class=PermissionSeeder
```

### 3. Register Middleware
Add the TrackUserActivity middleware to your `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\TrackUserActivity::class,
    ],
];
```

### 4. Configure Permissions
The system comes with pre-configured permissions for:
- User management (view, create, edit, delete, export, bulk actions)
- Role management (view, create, edit, delete, assign permissions)
- System management (settings, audit logs, statistics)
- Facility management
- Referral management
- Patient management
- Report management

## Usage

### User Management

#### List Users
```php
// Get all users with pagination
$users = User::with(['roles', 'userFacility'])->paginate(15);

// Search users
$users = User::where('name', 'like', '%John%')->get();

// Filter by status
$activeUsers = User::active()->get();
$suspendedUsers = User::suspended()->get();
```

#### Create User
```php
$userService = new UserManagementService();
$user = $userService->createUser([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'username' => 'johndoe',
    'password' => 'password123',
    'role_id' => 1,
    'facility_id' => 'FAC001',
    'status' => 'active',
    'phone' => '+254700000000',
    'address' => 'Nairobi, Kenya'
]);
```

#### Update User
```php
$user = User::find(1);
$userService->updateUser($user, [
    'name' => 'John Smith',
    'email' => 'johnsmith@example.com',
    'status' => 'active'
]);
```

#### Bulk Operations
```php
$userIds = [1, 2, 3, 4, 5];
$results = $userService->bulkAction($userIds, 'activate');
$results = $userService->bulkAction($userIds, 'suspend');
$results = $userService->bulkAction($userIds, 'change_role', ['role_id' => 2]);
```

### Role Management

#### Create Role
```php
$role = Role::create([
    'name' => 'nurse',
    'description' => 'Nursing staff role',
    'guard_name' => 'web'
]);

// Assign permissions
$role->givePermissionTo(['view patients', 'create patients', 'edit patients']);
```

#### Clone Role
```php
$originalRole = Role::find(1);
$newRole = Role::create([
    'name' => 'senior_nurse',
    'description' => 'Senior nursing staff role',
    'guard_name' => 'web'
]);

$newRole->syncPermissions($originalRole->permissions);
```

### Activity Tracking

#### Log User Activity
```php
$user->logActivity('login', 'user logged in', [
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent()
]);
```

#### Get User Activities
```php
$activities = $user->activities()->latest()->get();
$recentActivities = $user->getRecentActivities(10);
```

### Statistics and Reports

#### Get User Statistics
```php
$userService = new UserManagementService();
$stats = $userService->getUserStatistics();

// Access specific statistics
echo $stats['total_users'];
echo $stats['active_users'];
echo $stats['users_by_role'];
```

#### Export Users
```php
$filters = [
    'status' => 'active',
    'role' => 'doctor'
];

return $userService->exportUsers($filters);
```

## API Endpoints

### User Management Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/user-management` | List users with search and filtering |
| GET | `/user-management/create` | Show user creation form |
| POST | `/user-management` | Create new user |
| GET | `/user-management/{user}` | Show user details |
| GET | `/user-management/{user}/edit` | Show user edit form |
| PUT | `/user-management/{user}` | Update user |
| DELETE | `/user-management/{user}` | Delete user |
| POST | `/user-management/{user}/change-password` | Change user password |
| POST | `/user-management/bulk-action` | Perform bulk operations |
| GET | `/user-management/statistics` | Get user statistics |
| GET | `/user-management/export` | Export users to CSV |

### Role Management Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/role-management` | List roles |
| GET | `/role-management/create` | Show role creation form |
| POST | `/role-management` | Create new role |
| GET | `/role-management/{role}` | Show role details |
| GET | `/role-management/{role}/edit` | Show role edit form |
| PUT | `/role-management/{role}` | Update role |
| DELETE | `/role-management/{role}` | Delete role |
| POST | `/role-management/{role}/assign-permissions` | Assign permissions to role |
| POST | `/role-management/{role}/clone` | Clone role |
| GET | `/role-management/statistics` | Get role statistics |

## Security Features

### Authentication & Authorization
- Role-based access control (RBAC)
- Permission-based middleware
- Secure password hashing
- Session management

### Audit Trail
- Comprehensive activity logging
- IP address tracking
- User agent logging
- Data change tracking

### Data Protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- CSRF protection

## Best Practices

### User Management
1. Always validate user input
2. Use transactions for critical operations
3. Log all user activities
4. Implement proper error handling
5. Use bulk operations for efficiency

### Security
1. Regularly audit user permissions
2. Monitor user activities
3. Implement password policies
4. Use HTTPS in production
5. Regular security updates

### Performance
1. Use database indexes
2. Implement caching where appropriate
3. Use pagination for large datasets
4. Optimize database queries
5. Monitor system performance

## Troubleshooting

### Common Issues

1. **Permission Denied Errors**
   - Check if user has required permissions
   - Verify role assignments
   - Check middleware configuration

2. **Activity Logging Issues**
   - Verify middleware is registered
   - Check database connection
   - Review activity table structure

3. **Bulk Operation Failures**
   - Check transaction handling
   - Verify user permissions
   - Review error logs

### Debugging

1. **Enable Debug Mode**
   ```php
   // In .env file
   APP_DEBUG=true
   ```

2. **Check Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Database Queries**
   ```php
   // Enable query logging
   DB::enableQueryLog();
   // Your code here
   dd(DB::getQueryLog());
   ```

## Future Enhancements

1. **Advanced Features**
   - Two-factor authentication
   - Single sign-on (SSO)
   - API token management
   - User groups and teams

2. **Reporting**
   - Advanced analytics dashboard
   - Custom report builder
   - Scheduled reports
   - Data visualization

3. **Integration**
   - LDAP/Active Directory integration
   - OAuth2 providers
   - Webhook notifications
   - Third-party integrations

4. **Mobile Support**
   - Mobile-responsive interface
   - Mobile app integration
   - Push notifications
   - Offline capabilities

## Support

For technical support or questions about the User Management Module:

1. Check the documentation
2. Review the code comments
3. Check the issue tracker
4. Contact the development team

## License

This module is part of the Angaza Referral System and follows the same licensing terms. 