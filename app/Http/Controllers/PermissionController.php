<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModulePermission;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserModulePermission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        $users = User::with(['userRoles.department', 'userRoles.position', 'userRoles.role'])
                ->latest()
                ->paginate(10);
        return view('admin.permissions.index', [
            'users' => $users,
        ]);
    }

    public function manage(User $user, UserRole $userRole): View
    {
      
        $userRole->load(['department', 'position', 'role']);
        
        $modules = Module::with('modulePermissions')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $existingPermissions = UserModulePermission::where('user_role_id', $userRole->id)
            ->get()
            ->keyBy('module_permission_id');

        return view('admin.permissions.manage', [
            'user' => $user,
            'userRole' => $userRole,
            'modules' => $modules,
            'existingPermissions' => $existingPermissions,
        ]);
    }

    public function store(Request $request, User $user, UserRole $userRole): RedirectResponse
    {
        $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*.module_permission_id' => ['required', 'exists:module_permissions,id'],
            'permissions.*.can_view' => ['boolean'],
            'permissions.*.can_create' => ['boolean'],
            'permissions.*.can_edit' => ['boolean'],
            'permissions.*.can_delete' => ['boolean'],
        ]);

        $permissions = $request->input('permissions');

        // Get current IDs from request
        $incomingIds = collect($permissions)->pluck('module_permission_id')->toArray();

        // Delete removed permissions
        UserModulePermission::where('user_role_id', $userRole->id)
            ->whereNotIn('module_permission_id', $incomingIds)
            ->delete();

        // Update/Create remaining
        foreach ($permissions as $permissionData) {
            UserModulePermission::updateOrCreate(
                [
                    'user_role_id' => $userRole->id,
                    'module_permission_id' => $permissionData['module_permission_id'],
                ],
                [
                    'can_view' => $permissionData['can_view'] ?? false,
                    'can_create' => $permissionData['can_create'] ?? false,
                    'can_edit' => $permissionData['can_edit'] ?? false,
                    'can_delete' => $permissionData['can_delete'] ?? false,
                ]
            );
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.permissions.store',
            'description' => "Updated permissions for user {$user->email} in role {$userRole->role?->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.permissions.manage', [$user->id, $userRole->id])
            ->with('status', 'Permissions updated successfully.');
    }

    public function getUserRoles(User $user)
    {
        return response()->json([
            'user_roles' => $user->userRoles()
                ->with(['department', 'position', 'role'])
                ->get()
                ->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'label' => "{$role->role?->name} / {$role->department?->name} / {$role->position?->name}",
                    ];
                }),
        ]);
    }
}
