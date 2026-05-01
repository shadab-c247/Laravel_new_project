<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Barryvdh\DomPDF\Facade\Pdf;


class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'users' => User::with([
                'userRoles.department',
                'userRoles.position',
                'userRoles.role',
            ])->latest()->get(),
            'roles' => Role::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
            'activityLogs' => ActivityLog::with('user')->latest()->limit(10)->get(),
        ]);
    }

    public function users(): View
    {
        return view('admin.users', [
            'users' => User::with([
                'userRoles.department',
                'userRoles.position',
                'userRoles.role',
            ])->latest()->paginate(10),
            'roles' => Role::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
        ]);
    }

    public function departments(): View
    {
        return view('admin.departments', [
            'departments' => Department::orderBy('name')->paginate(10),
        ]);
    }

    public function roles(): View
    {
        return view('admin.roles', [
            'roles' => Role::orderBy('name')->paginate(10),
        ]);
    }

    public function positions(): View
    {
        return view('admin.positions', [
            'positions' => Position::orderBy('name')->paginate(10),
        ]);
    }

    public function storeDepartment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
        ]);

        $department = Department::create($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.departments.store',
            'description' => 'Created department: ' . $department->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.departments')->with('status', 'Department created successfully.');
    }

    public function updateDepartment(Request $request, Department $department): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $department->id],
        ]);

        $oldName = $department->name;
        $department->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.departments.update',
            'description' => "Updated department from '{$oldName}' to '{$department->name}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.departments')->with('status', 'Department updated successfully.');
    }

    public function destroyDepartment(Request $request, Department $department): RedirectResponse
    {
        $departmentName = $department->name;
        $department->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.departments.destroy',
            'description' => "Deleted department: '{$departmentName}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.departments')->with('status', 'Department deleted successfully.');
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
        ]);

        $role = Role::create($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.roles.store',
            'description' => 'Created role: ' . $role->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.roles')->with('status', 'Role created successfully.');
    }

    public function updateRole(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
        ]);

        $oldName = $role->name;
        $role->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.roles.update',
            'description' => "Updated role from '{$oldName}' to '{$role->name}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.roles')->with('status', 'Role updated successfully.');
    }

    public function destroyRole(Request $request, Role $role): RedirectResponse
    {
        $roleName = $role->name;
        $role->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.roles.destroy',
            'description' => "Deleted role: '{$roleName}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.roles')->with('status', 'Role deleted successfully.');
    }

    public function storePosition(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:positions,name'],
        ]);

        $position = Position::create($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.positions.store',
            'description' => 'Created position: ' . $position->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.positions')->with('status', 'Position created successfully.');
    }

    public function updatePosition(Request $request, Position $position): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:positions,name,' . $position->id],
        ]);

        $oldName = $position->name;
        $position->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.positions.update',
            'description' => "Updated position from '{$oldName}' to '{$position->name}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.positions')->with('status', 'Position updated successfully.');
    }

    public function destroyPosition(Request $request, Position $position): RedirectResponse
    {
        $positionName = $position->name;
        $position->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.positions.destroy',
            'description' => "Deleted position: '{$positionName}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.positions')->with('status', 'Position deleted successfully.');
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

        return redirect()->route('admin.users')->with('status', 'User created successfully.');
    }

    public function updateAssignment(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
        ]);

        // Get old/latest assignment
        $oldAssignment = $user->userRoles()
            ->latest()
            ->first();

        $oldLabel = $oldAssignment
            ? $this->assignmentLabel($oldAssignment)
            : 'None';

        // Add new assignment
        $assignment = $this->addAssignment($user, $data);

        $newLabel = $this->assignmentLabel($assignment);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin.users.assignment.update',
            'description' => "Assignment Added Old [{$oldLabel}]  New [{$newLabel}] for {$user->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users')->with('status', 'User assignment updated successfully.');
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
            'description' => "Deleted user '{$deletedEmail}' by Admin",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users')->with('status', 'User deleted successfully.');
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
            'activityLogs' => ActivityLog::with('user')->latest()->get(),
        ]);
    }

    public function export(Request $request)
    {
        $format = $request->format;
        $from = $request->from_date;
        $to = $request->to_date;

        $query = ActivityLog::query();

        // Get actual date range from data if not provided
        if (!$from || !$to) {
            $minDate = ActivityLog::min('created_at');
            $maxDate = ActivityLog::max('created_at');
            $from = $from ?? ($minDate ? Carbon::parse($minDate)->format('Y-m-d') : null);
            $to = $to ?? ($maxDate ? Carbon::parse($maxDate)->format('Y-m-d') : null);
        }

        if ($from && $to) {
            $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        }

        $query->orderBy('id');

        // CSV Export Native PHP
        if ($format === 'excel') {
            $filename = 'activities_'.now()->timezone('Asia/Kolkata')->format('Y-m-d_H-i-s').'.csv';

            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
            ];

            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');

                // Heading
                fputcsv($file, ['ID', 'User', 'Action', 'Description', 'Date']);

                // Chunk to handle large datasets
                $query->chunk(1000, function ($logs) use ($file) {
                    foreach ($logs as $row) {
                        fputcsv($file, [
                            $row->id,
                            $row->user?->email ?? 'System',
                            $row->action,
                            $row->description,
                            $row->created_at->format('d M Y, h:i A'),
                        ]);
                    }
                });

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // PDF Export
        if ($format === 'pdf') {
            $data = $query->get();
            $pdf = \PDF::loadView('admin.exports.activities', compact('data'));
            return $pdf->download('activities_'.now()->timezone('Asia/Kolkata')->format('Y-m-d_H-i-s').'.pdf');
        }

        return back()->with('error', 'Invalid format');
    }

    private function validatedUserData(Request $request): array
    {
        $messages = [
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.mixed' => 'Password must contain both uppercase and lowercase letters.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
            'password.uncompromised' => 'This password has been compromised in a data breach. Please choose a different password.',
        ];

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
        ], $messages);
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
