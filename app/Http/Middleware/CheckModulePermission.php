<?php

namespace App\Http\Middleware;

use App\Models\Module;
use App\Models\UserModulePermission;
use App\Models\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleSlug, string $action): Response
    {
        $user = auth()->user();
        
        // Admin always has access
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Get the selected user role if in switch mode
        $userRoleId = session('admin_selected_user_role_id');
        
        if (!$userRoleId) {
            // If not in switch mode, get the user's active role
            $userRole = $user->userRoles()->where('is_active', true)->first();
            if (!$userRole) {
                abort(403, 'No active role assigned');
            }
            $userRoleId = $userRole->id;
        }

        // Get the module
        $module = Module::where('slug', $moduleSlug)->first();
        if (!$module) {
            abort(404, 'Module not found');
        }

        // Get the module permission
        $modulePermission = $module->modulePermissions()->where('action', $action)->first();
        if (!$modulePermission) {
            abort(404, 'Module permission not found');
        }

        // Check if user has the permission
        $userModulePermission = UserModulePermission::where('user_role_id', $userRoleId)
            ->where('module_permission_id', $modulePermission->id)
            ->first();

        if (!$userModulePermission) {
            abort(403, 'You do not have permission to access this module');
        }

        // Check specific action permission
        $permissionField = 'can_' . $action;
        if (!isset($userModulePermission->$permissionField) || !$userModulePermission->$permissionField) {
            abort(403, 'You do not have permission to perform this action');
        }

        return $next($request);
    }
}
