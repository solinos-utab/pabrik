<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Role, Permission};

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            // Attendance permissions
            ['name' => 'attendance.view', 'display_name' => 'View Attendance'],
            ['name' => 'attendance.manage', 'display_name' => 'Manage Attendance'],
            
            // Shift permissions
            ['name' => 'shift.view', 'display_name' => 'View Shifts'],
            ['name' => 'shift.manage', 'display_name' => 'Manage Shifts'],
            
            // Production permissions
            ['name' => 'production.view', 'display_name' => 'View Production'],
            ['name' => 'production.manage', 'display_name' => 'Manage Production'],
            
            // Inventory permissions
            ['name' => 'inventory.view', 'display_name' => 'View Inventory'],
            ['name' => 'inventory.manage', 'display_name' => 'Manage Inventory'],
            
            // User management permissions
            ['name' => 'user.view', 'display_name' => 'View Users'],
            ['name' => 'user.manage', 'display_name' => 'Manage Users'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create roles
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            [
                'name' => 'hr_manager',
                'display_name' => 'HR Manager',
                'permissions' => ['attendance.view', 'attendance.manage', 'shift.view', 'shift.manage', 'user.view']
            ],
            [
                'name' => 'production_manager',
                'display_name' => 'Production Manager',
                'permissions' => ['production.view', 'production.manage']
            ],
            [
                'name' => 'warehouse_manager',
                'display_name' => 'Warehouse Manager',
                'permissions' => ['inventory.view', 'inventory.manage']
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'permissions' => ['attendance.view', 'production.view', 'inventory.view']
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::create($roleData);
            $role->permissions()->attach(
                Permission::whereIn('name', $permissions)->pluck('id')
            );
        }
    }
}