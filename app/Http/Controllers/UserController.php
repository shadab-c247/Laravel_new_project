<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $user = User::with([
            'userRoles.department',
            'userRoles.position',
            'userRoles.role',
        ])->find(auth()->id());

        $selectedUserRole = session('admin_selected_user_role_id')
            ? $user->userRoles->firstWhere('id', (int) session('admin_selected_user_role_id'))
            : $user->userRoles->first();

        return view('user.dashboard', compact('user', 'selectedUserRole'));
    }
}
