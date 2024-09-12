<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(LaratrustSeeder::class);

        // Create the permissions
        $permissions = [
            // Nursery Owner Roles
            'Nursery-Set-Status',
            'Nursery-Approved',
            'Nursery-Profile',
            'Manage-Classes',
            'Meal',
            'NewsLetter',
            'Parent-Request',
            'Payment-History',
            'Payment-Request',
            'Nursery-Policy',
            'Roles',
            'Faq',
            // Super Admin Roles
            'Nurseries',
            'Applications',
            'Payment-History',
            'Admins',
        ];

        foreach ($permissions as $permission) {
            $permissions[] = \Laratrust\Models\Permission::firstOrCreate([
                'name' => $permission,
                'display_name' => ucfirst($permission),
                'description' => ucfirst($permission),
            ])->id;
        }
    }
}
