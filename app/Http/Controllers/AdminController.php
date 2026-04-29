<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index(): View
    {
        $users = User::with([
            'userRoles.department',
            'userRoles.position',
            'userRoles.role',
        ])->latest()->get();

        return view('admin.dashboard', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
            'activityLogs' => ActivityLog::with('user')->latest()->limit(10)->get(),
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $this->validatedUserData($request);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        $assignment = $this->addAssignment($user, $data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.users.store',
            'description' => 'Created user '.$user->email.' with '.$this->assignmentLabel($assignment),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('status', 'User created successfully.');
    }

    public function updateAssignment(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
        ]);

        $assignment = $this->addAssignment($user, $data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.users.assignment.update',
            'description' => 'Assigned '.$this->assignmentLabel($assignment).' to '.$user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('status', 'User assignment updated.');
    }

    public function switchUser(Request $request, User $user): RedirectResponse
    {
        if ((int) $user->id === (int) auth()->id()) {
            return back()->with('status', 'You are already using this account.');
        }

        $data = $request->validate([
            'user_role_id' => ['required', 'exists:user_roles,id'],
        ]);

        $assignment = UserRole::with(['department', 'position', 'role'])
            ->where('user_id', $user->id)
            ->findOrFail($data['user_role_id']);

        session([
            'admin_impersonator_id' => auth()->id(),
            'admin_selected_user_role_id' => $assignment->id,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.users.switch',
            'description' => 'Switched into '.$user->email.' as '.$this->assignmentLabel($assignment),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Switched user successfully.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ((int) $user->id === (int) auth()->id()) {
            return back()->withErrors(['user' => 'You cannot delete your own active admin account.']);
        }

        $deletedEmail = $user->email;

        if ((int) session('admin_impersonator_id') === (int) $user->id) {
            session()->forget(['admin_impersonator_id', 'admin_selected_user_role_id']);
        }

        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.users.destroy',
            'description' => 'Deleted user '.$deletedEmail,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('status', 'User deleted successfully.');
    }

    public function switchBack(Request $request): RedirectResponse
    {
        $adminId = session('admin_impersonator_id');

        abort_unless($adminId, 403);

        $currentUser = auth()->user();
        $admin = User::findOrFail($adminId);

        Auth::login($admin);
        session()->forget(['admin_impersonator_id', 'admin_selected_user_role_id']);

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'admin.switch-back',
            'description' => 'Returned from '.$currentUser?->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Back to admin account.');
    }

    public function activityLogs(): View
    {
        return view('admin.activity-logs', [
            'activityLogs' => ActivityLog::with('user')->latest()->paginate(10),
        ]);
    }

    private function validatedUserData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
        ]);
    }

    private function addAssignment(User $user, array $data): UserRole
    {
        $assignment = UserRole::firstOrCreate([
            'user_id' => $user->id,
            'role_id' => $data['role_id'],
            'department_id' => $data['department_id'],
            'position_id' => $data['position_id'],
        ], [
            'is_active' => true,
        ]);

        $role = Role::find($data['role_id']);

        if ($role) {
            $user->assignRole($role->name);
        }

        return $assignment->load(['department', 'position', 'role']);
    }

    private function assignmentLabel(UserRole $assignment): string
    {
        return ($assignment->role?->name ?? 'N/A').' / '
            .($assignment->department?->name ?? 'N/A').' / '
            .($assignment->position?->name ?? 'N/A');
    }
}
