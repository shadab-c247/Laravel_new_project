<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesDepartmentsPositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);

        Department::firstOrCreate(['name' => 'Admin']);
        Department::firstOrCreate(['name' => 'General']);
        Department::firstOrCreate(['name' => 'Sales']);
        Department::firstOrCreate(['name' => 'Support']);

        Position::firstOrCreate(['name' => 'Admin']);
        Position::firstOrCreate(['name' => 'Staff']);
        Position::firstOrCreate(['name' => 'Manager']);
    }
}
