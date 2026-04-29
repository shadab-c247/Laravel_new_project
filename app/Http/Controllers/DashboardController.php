<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = User::with([
            'userRoles.department',
            'userRoles.position',
            'userRoles.role',
        ])->findOrFail(auth()->id());

         if (!session('admin_impersonator_id')) {
            session()->forget('admin_selected_user_role_id');
        }

        if (!session('admin_impersonator_id') && $user->isAdmin()) {
            return app(AdminController::class)->index();
        }

        $selectedUserRole = $this->selectedUserRole($user);

        return view('user.dashboard', [
            'user' => $user,
            'selectedUserRole' => $selectedUserRole,
            'userRoles' => $user->userRoles
        ]);
    }

    private function selectedUserRole(User $user)
    {
        $selectedId = session('admin_selected_user_role_id');

        if ($selectedId) {
            $assignment = $user->userRoles->firstWhere('id', (int) $selectedId);

            if ($assignment) {
                return $assignment;
            }
        }

        return $user->userRoles->first();
    }
}
