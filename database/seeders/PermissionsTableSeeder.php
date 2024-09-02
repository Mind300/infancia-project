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
