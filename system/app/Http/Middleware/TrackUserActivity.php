<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track activities for authenticated users
        if (Auth::check()) {
            $this->logActivity($request);
        }

        return $response;
    }

    /**
     * Log user activity based on the request
     */
    private function logActivity(Request $request)
    {
        $user = Auth::user();
        $action = $this->determineAction($request);
        $description = $this->determineDescription($request);

        if ($action && $description) {
            $data = [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            // Add request data for specific actions
            if (in_array($action, ['create', 'update', 'delete'])) {
                $data['request_data'] = $request->except(['password', 'password_confirmation', '_token']);
            }

            $user->logActivity($action, $description, $data);
        }
    }

    /**
     * Determine the action based on the request
     */
    private function determineAction(Request $request)
    {
        $method = $request->method();
        $path = $request->path();

        // Login/Logout actions
        if ($path === 'user-login' && $method === 'POST') {
            return 'login';
        }

        if ($path === 'logout' && $method === 'GET') {
            return 'logout';
        }

        // CRUD actions based on HTTP method
        if ($method === 'POST' && str_contains($path, 'store')) {
            return 'create';
        }

        if ($method === 'PUT' || $method === 'PATCH') {
            return 'update';
        }

        if ($method === 'DELETE') {
            return 'delete';
        }

        if ($method === 'GET' && str_contains($path, 'export')) {
            return 'export';
        }

        if ($method === 'GET' && str_contains($path, 'import')) {
            return 'import';
        }

        // View actions
        if ($method === 'GET' && !str_contains($path, 'export') && !str_contains($path, 'import')) {
            return 'view';
        }

        return null;
    }

    /**
     * Determine the description based on the request
     */
    private function determineDescription(Request $request)
    {
        $path = $request->path();
        $segments = explode('/', $path);

        // User management
        if (str_contains($path, 'user-management')) {
            if (str_contains($path, 'create')) {
                return 'viewed user creation form';
            }
            if (str_contains($path, 'edit')) {
                return 'viewed user edit form';
            }
            if (str_contains($path, 'export')) {
                return 'exported users data';
            }
            return 'accessed user management';
        }

        // Role management
        if (str_contains($path, 'role-management')) {
            if (str_contains($path, 'create')) {
                return 'viewed role creation form';
            }
            if (str_contains($path, 'edit')) {
                return 'viewed role edit form';
            }
            return 'accessed role management';
        }

        // Patient management
        if (str_contains($path, 'patients')) {
            if (str_contains($path, 'new-patient')) {
                return 'viewed patient creation form';
            }
            if (str_contains($path, 'view')) {
                return 'viewed patient details';
            }
            if (str_contains($path, 'search')) {
                return 'searched for patients';
            }
            return 'accessed patient management';
        }

        // Referral management
        if (str_contains($path, 'referrals')) {
            if (str_contains($path, 'create')) {
                return 'viewed referral creation form';
            }
            if (str_contains($path, 'view')) {
                return 'viewed referral details';
            }
            if (str_contains($path, 'worklist')) {
                return 'accessed referral worklist';
            }
            if (str_contains($path, 'incoming')) {
                return 'accessed incoming referrals';
            }
            if (str_contains($path, 'outgoing')) {
                return 'accessed outgoing referrals';
            }
            return 'accessed referral management';
        }

        // Reports
        if (str_contains($path, 'reports')) {
            return 'accessed reports';
        }

        // Dashboard
        if (str_contains($path, 'dashboard')) {
            return 'accessed dashboard';
        }

        // Admin
        if (str_contains($path, 'admin')) {
            return 'accessed admin panel';
        }

        // Default description
        return 'accessed ' . $path;
    }
} 