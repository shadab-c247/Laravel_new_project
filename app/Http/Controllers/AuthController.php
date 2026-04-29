<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return redirect()->back()->with('error', 'Invalid credentials');
        }

        $user = auth()->user();

        // user with relations
        $userData = User::with([
            'userRole.department',
            'userRole.position',
            'userRole.role'
        ])->find($user->id);

        $userRole = $userData->userRole;

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'description' => 'User logged in',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // ✅ Admin check
        if (
            $userRole &&
            $userRole->department_id == 1 &&
            $userRole->position_id == 1 &&
            $userRole->role_id == 1
        ) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    public function register(Request $request)
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

        $otp = $user->generateOtp();

        return redirect()->route('otp.form')
            ->with('email', $user->email)
            ->with('otp', $otp);
            }
    
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->withErrors(['email' => 'User not found']);
            }

            if (!$user->verifyOtp($request->otp)) {
                return back()->withErrors(['otp' => 'Invalid or expired OTP']);
            }


        Auth::login($user);
        $token = JWTAuth::fromUser($user);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'otp.verified',
            'description' => 'User verified OTP and logged in',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dashboard')->with('token', $token);
    }
        
}
