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
        // Load users with their role assignments and related data
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
        // Load users with their role assignments and related data
        return view('admin.users', [
            'users' => User::with([
                'userRoles.department',
                'userRoles.position',
                'userRoles.role',
            ])->latest()->get(),
            'roles' => Role::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
        ]);
    }

    public function departments(): View
    {
        // Load departments with pagination
        return view('admin.departments', [
            'departments' => Department::orderBy('name')->paginate(10),
        ]);
    }

    public function roles(): View
    {
        // Load roles with pagination
        return view('admin.roles', [
            'roles' => Role::orderBy('name')->paginate(10),
        ]);
    }

    public function positions(): View
    {
        // Load positions with pagination
        return view('admin.positions', [
            'positions' => Position::orderBy('name')->paginate(10),
        ]);
    }

    public function storeDepartment(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
        ]);

        // Create the department
        $department = Department::create($data);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'departments.store',
            'description' => 'Created department: ' . $department->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.departments')->with('status', 'Department created successfully.');
        }
        return redirect()->route('user.departments')->with('status', 'Department created successfully.');
    }

    public function updateDepartment(Request $request, Department $department): RedirectResponse
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $department->id],
        ]);

        // Store the old name for logging
        $oldName = $department->name;
        
        // Update the department
        $department->update($data);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'departments.update',
            'description' => "Updated department from '{$oldName}' to '{$department->name}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.departments')->with('status', 'Department updated successfully.');
        }
        return redirect()->route('user.departments')->with('status', 'Department updated successfully.');
    }

    public function destroyDepartment(Request $request, Department $department): RedirectResponse
    {
        // Store the department name for logging
        $departmentName = $department->name;
        
        // Delete the department
        $department->delete();

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'departments.destroy',
            'description' => "Deleted department: '{$departmentName}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.departments')->with('status', 'Department deleted successfully.');
        }
        return redirect()->route('user.departments')->with('status', 'Department deleted successfully.');
    }

    public function storeRole(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
        ]);

        // Create the role
        $role = Role::create($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'roles.store',
            'description' => 'Created role: ' . $role->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.roles')->with('status', 'Role created successfully.');
        }
        return redirect()->route('user.roles')->with('status', 'Role created successfully.');
    }

    public function updateRole(Request $request, Role $role): RedirectResponse
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
        ]);

        // Store the old name for logging
        $oldName = $role->name;
        $role->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'roles.update',
            'description' => "Updated role from '{$oldName}' to '{$role->name}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.roles')->with('status', 'Role updated successfully.');
        }
        return redirect()->route('user.roles')->with('status', 'Role updated successfully.');
    }

    public function destroyRole(Request $request, Role $role): RedirectResponse
    {
        // Store the role name for logging
        $roleName = $role->name;
        
        // Delete the role
        $role->delete();

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'roles.destroy',
            'description' => "Deleted role: '{$roleName}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.roles')->with('status', 'Role deleted successfully.');
        }
        return redirect()->route('user.roles')->with('status', 'Role deleted successfully.');
    }

    public function storePosition(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:positions,name'],
        ]);

        // Create the position
        $position = Position::create($data);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'positions.store',
            'description' => 'Created position: ' . $position->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.positions')->with('status', 'Position created successfully.');
        }
        return redirect()->route('user.positions')->with('status', 'Position created successfully.');
    }

    public function updatePosition(Request $request, Position $position): RedirectResponse
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:positions,name,' . $position->id],
        ]);

        // Store the old name for logging
        $oldName = $position->name;
        $position->update($data);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'positions.update',
            'description' => "Updated position from '{$oldName}' to '{$position->name}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.positions')->with('status', 'Position updated successfully.');
        }
        return redirect()->route('user.positions')->with('status', 'Position updated successfully.');
    }

    public function destroyPosition(Request $request, Position $position): RedirectResponse
    {
        // Store the position name for logging
        $positionName = $position->name;
        $position->delete();

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'positions.destroy',
            'description' => "Deleted position: '{$positionName}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.positions')->with('status', 'Position deleted successfully.');
        }
        return redirect()->route('user.positions')->with('status', 'Position deleted successfully.');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $data = $this->validatedUserData($request);

        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        // Add assignment to the user
        $assignment = $this->addAssignment($user, $data);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'users.store',
            'description' => 'Created user '.$user->email.' with '.$this->assignmentLabel($assignment),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    
        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.users')->with('status', 'User created successfully.');
        }
        return redirect()->route('user.users')->with('status', 'User created successfully.');
    }

    public function updateAssignment(Request $request, User $user): RedirectResponse
    {
        // Validate the incoming request data
        $data = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
        ]);

        // Get old/latest assignment
        $oldAssignment = $user->userRoles()
            ->latest()
            ->first();

        // Get the old assignment label
        $oldLabel = $oldAssignment
            ? $this->assignmentLabel($oldAssignment)
            : 'None';

        // Add new assignment
        $assignment = $this->addAssignment($user, $data);

        // Get the new assignment label
        $newLabel = $this->assignmentLabel($assignment);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'users.assignment.update',
            'description' => "Assignment Added Old [{$oldLabel}]  New [{$newLabel}] for {$user->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        // Redirect to appropriate route based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.users')->with('status', 'User assignment updated successfully.');
        }
        return redirect()->route('user.users')->with('status', 'User assignment updated successfully.');
    }

    public function switchUser(Request $request, User $user): RedirectResponse
    {
        // Check if the user is trying to switch to their own account
        if ((int) $user->id === (int) auth()->id()) {
            return back()->with('status', 'You are already using this account.');
        }

        // Validate the incoming request data
        $data = $request->validate([
            'user_role_id' => ['required', 'exists:user_roles,id'],
        ]);

        // Get the assignment
        $assignment = UserRole::with(['department', 'position', 'role'])
            ->where('user_id', $user->id)
            ->findOrFail($data['user_role_id']);

        // Set session data for impersonation
        session([
            'admin_impersonator_id' => auth()->id(),
            'admin_selected_user_role_id' => $assignment->id,
        ]);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'users.switch',
            'description' => 'Switched into '.$user->email.' as '.$this->assignmentLabel($assignment),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Login the user
        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Switched user successfully.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        // Check if the user is trying to delete themselves
        if ((int) $user->id === (int) auth()->id()) {
            return back()->withErrors(['user' => 'You cannot delete your own active admin account.']);
        }

        $deletedEmail = $user->email;
        // If the currently deleted/affected user is the same as the impersonated user,
        // then clear impersonation-related session data to prevent invalid or stale session state
        if ((int) session('admin_impersonator_id') === (int) $user->id) {
            session()->forget(['admin_impersonator_id', 'admin_selected_user_role_id']);
        }

        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'users.destroy',
            'description' => "Deleted user '{$deletedEmail}' by Admin",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Redirect based on the current user's role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.users')->with('status', 'User deleted successfully.');
        }
        return redirect()->route('user.users')->with('status', 'User deleted successfully.');
    }

    public function switchBack(Request $request): RedirectResponse
    {
        // Get the admin ID from session
        $adminId = session('admin_impersonator_id');

        // Abort if no admin ID is found in session
        abort_unless($adminId, 403);

        // Get the current user and admin user
        $currentUser = auth()->user();
        $admin = User::findOrFail($adminId);

        // Login the admin user
        Auth::login($admin);
        // Clear impersonation-related session data
        session()->forget(['admin_impersonator_id', 'admin_selected_user_role_id']);

        // Log the activity
        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'switch-back',
            'description' => 'Returned from '.$currentUser?->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Back to admin account.');
    }

    public function activityLogs(): View
    {
        // Return the activity logs view
        return view('admin.activity-logs', [
            'activityLogs' => ActivityLog::with('user')->latest()->get(),
        ]);
    }

    // User panel methods - return user-specific views
    public function userUsers(): View
    {
        // Get users with their roles, departments, positions, and roles
        $users= User::with([
                'userRoles.department',
                'userRoles.position',
                'userRoles.role',
            ])->latest()->get();
        $roles= Role::orderBy('name')->get();
        $departments= Department::orderBy('name')->get();
        $positions= Position::orderBy('name')->get();
        return view('user.users', [
            'users' => $users,
            'roles' => $roles,
            'departments' => $departments,
            'positions' => $positions,
        ]);
    }

    public function userDepartments(): View
    {
        // Return the departments view
        return view('user.departments', [
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    public function userRoles(): View
    {
        // Return the roles view
        return view('user.roles', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function userPositions(): View
    {
        // Return the positions view
        return view('user.positions', [
            'positions' => Position::orderBy('name')->get(),
        ]);
    }

    public function userActivityLogs(): View
    {
        // Return the activity logs view
        return view('user.activity-logs', [
            'activityLogs' => ActivityLog::with('user')->latest()->get(),
        ]);
    }

    public function export(Request $request)
    {
        // Get the request parameters
        $format = $request->format;
        $from = $request->from_date;
        $to = $request->to_date;
        // Create a query instance
        $query = ActivityLog::query();

        // Get actual date range from data if not provided
        if (!$from || !$to) {
            $minDate = ActivityLog::min('created_at');
            $maxDate = ActivityLog::max('created_at');
            // Set default dates if no data is found
            $from = $from ?? ($minDate ? Carbon::parse($minDate)->format('Y-m-d') : null);
            $to = $to ?? ($maxDate ? Carbon::parse($maxDate)->format('Y-m-d') : null);
        }

        if ($from && $to) {
            // Filter logs by date range Carbon uses startOfDay and endOfDay methods
            $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        }

        $query->orderBy('id');

        // CSV Export Native PHP
        if ($format === 'excel') {
            // Generate filename with current date and time in Asia/Kolkata timezone
            $filename = 'activities_'.now()->timezone('Asia/Kolkata')->format('Y-m-d_H-i-s').'.csv';

            // Set headers to trigger browser download
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
            ];

            // Callback function to generate CSV content
            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');

                // Heading
                fputcsv($file, ['ID', 'User', 'Action', 'Description', 'Date']);

                // Chunk to handle large datasets in batches of 1000
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
            // Return streamed response with headers
            //stream() method is used to stream the response content directly to the browser
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
        // Validate the user data
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

        // Return the validated user data
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
        // Create or find the user role assignment
        $assignment = UserRole::firstOrCreate([
            'user_id' => $user->id,
            'role_id' => $data['role_id'],
            'department_id' => $data['department_id'],
            'position_id' => $data['position_id'],
        ], [
            'is_active' => true,
        ]);
        // Find the role and assign it to the user
        $role = Role::find($data['role_id']);

        // Assign the role to the user
        if ($role) {
            $user->assignRole($role->name);
        }

        return $assignment->load(['department', 'position', 'role']);
    }

    private function assignmentLabel(UserRole $assignment): string
    {
        // Return a formatted string with role, department, and position
        return ($assignment->role?->name ?? 'N/A').' / '
            .($assignment->department?->name ?? 'N/A').' / '
            .($assignment->position?->name ?? 'N/A');
    }
}
