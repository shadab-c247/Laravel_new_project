<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateModuleRoutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moduleRoutes = [
            'dashboard' => [
                'admin_route' => 'admin.dashboard',
                'user_route' => 'user.dashboard',
            ],
            'users' => [
                'admin_route' => 'admin.users',
                'user_route' => 'user.users',
            ],
            'departments' => [
                'admin_route' => 'admin.departments',
                'user_route' => 'user.departments',
            ],
            'roles' => [
                'admin_route' => 'admin.roles',
                'user_route' => 'user.roles',
            ],
            'positions' => [
                'admin_route' => 'admin.positions',
                'user_route' => 'user.positions',
            ],
            'activity-logs' => [
                'admin_route' => 'admin.activity-logs',
                'user_route' => 'user.activity-logs',
            ],
        ];

        foreach ($moduleRoutes as $slug => $routes) {
            $module = Module::where('slug', $slug)->first();
            if ($module) {
                $module->update($routes);
                $this->command->info("Updated routes for module: {$module->name}");
            }
        }
    }
}
