<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_panel_create_user_and_assign_access(): void
    {
        $admin = $this->adminUser();
        $role = Role::where('name', 'user')->first();
        $department = Department::where('name', 'General')->first();
        $position = Position::where('name', 'Staff')->first();

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Admin Panel')
            ->assertSee('Users');

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Panel User',
            'email' => 'panel@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => $role->id,
            'department_id' => $department->id,
            'position_id' => $position->id,
        ])->assertRedirect();

        $createdUser = User::where('email', 'panel@example.com')->firstOrFail();

        $this->assertDatabaseHas('user_roles', [
            'user_id' => $createdUser->id,
            'role_id' => $role->id,
            'department_id' => $department->id,
            'position_id' => $position->id,
        ]);
    }

    public function test_admin_can_switch_to_user_and_back(): void
    {
        $admin = $this->adminUser();
        $user = User::factory()->create();
        $this->assignUserRole($user);

        $this->actingAs($admin)
            ->post(route('admin.users.switch', $user))
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
        $this->assertSame($admin->id, session('admin_impersonator_id'));

        $this->post(route('admin.switch-back'))
            ->assertRedirect(route('admin.dashboard', absolute: false));

        $this->assertAuthenticatedAs($admin);
        $this->assertNull(session('admin_impersonator_id'));
    }

    private function adminUser(): User
    {
        $this->seed();

        return User::where('email', 'admin@example.com')->firstOrFail();
    }

    private function assignUserRole(User $user): void
    {
        $role = Role::where('name', 'user')->firstOrFail();
        $department = Department::where('name', 'General')->firstOrFail();
        $position = Position::where('name', 'Staff')->firstOrFail();

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'department_id' => $department->id,
            'position_id' => $position->id,
            'is_active' => true,
        ]);
    }
}
