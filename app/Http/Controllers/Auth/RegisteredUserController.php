<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
       $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $this->assignDefaultRole($user);

        $otp = $user->generateOtp();
       

        return redirect()->route('otp.form')
            ->with('email', $user->email)
            ->with('otp', $otp);
    }

    private function assignDefaultRole(User $user): void
    {
        $role = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $department = Department::firstOrCreate(['name' => 'General']);
        $position = Position::firstOrCreate(['name' => 'Staff']);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'department_id' => $department->id,
            'position_id' => $position->id,
            'is_active' => true,
        ]);

        $user->assignRole($role->name);
    }
}
