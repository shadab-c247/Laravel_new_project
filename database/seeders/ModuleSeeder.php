<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModulePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'route_name' => 'admin.dashboard',
                'admin_route' => 'admin.dashboard',
                'user_route' => 'user.dashboard',
                'icon' => 'dashboard',
                'order' => 1,
                'permissions' => ['view'],
            ],
            [
                'name' => 'Users',
                'slug' => 'users',
                'route_name' => 'admin.users',
                'admin_route' => 'admin.users',
                'user_route' => 'user.users',
                'icon' => 'users',
                'order' => 2,
                'permissions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'name' => 'Departments',
                'slug' => 'departments',
                'route_name' => 'admin.departments',
                'admin_route' => 'admin.departments',
                'user_route' => 'user.departments',
                'icon' => 'departments',
                'order' => 3,
                'permissions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'name' => 'Roles',
                'slug' => 'roles',
                'route_name' => 'admin.roles',
                'admin_route' => 'admin.roles',
                'user_route' => 'user.roles',
                'icon' => 'roles',
                'order' => 4,
                'permissions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'name' => 'Positions',
                'slug' => 'positions',
                'route_name' => 'admin.positions',
                'admin_route' => 'admin.positions',
                'user_route' => 'user.positions',
                'icon' => 'positions',
                'order' => 5,
                'permissions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'name' => 'Activity Logs',
                'slug' => 'activity-logs',
                'route_name' => 'admin.activity-logs',
                'admin_route' => 'admin.activity-logs',
                'user_route' => 'user.activity-logs',
                'icon' => 'activity-logs',
                'order' => 6,
                'permissions' => ['view'],
            ],
        ];

        foreach ($modules as $moduleData) {
            $permissions = $moduleData['permissions'];
            unset($moduleData['permissions']);

            $module = Module::create($moduleData);

            foreach ($permissions as $action) {
                ModulePermission::create([
                    'module_id' => $module->id,
                    'name' => ucfirst($action) . ' ' . $module->name,
                    'slug' => $action . '.' . $module->slug,
                    'action' => $action,
                ]);
            }
        }
    }
}
