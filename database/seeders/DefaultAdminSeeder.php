<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $adminDepartment = Department::where('name', 'Admin')->firstOrFail();
        $adminPosition = Position::where('name', 'Admin')->firstOrFail();

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        UserRole::where('user_id', $admin->id)->delete();
        UserRole::create([
            'user_id' => $admin->id,
            'role_id' => $adminRole->id,
            'department_id' => $adminDepartment->id,
            'position_id' => $adminPosition->id,
            'is_active' => true,
        ]);

        $admin->syncRoles([$adminRole->name]);
    }
}
