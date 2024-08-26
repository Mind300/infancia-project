<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Nurseries;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laratrust\Models\Permission;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

class DatabaseSeeder extends Seeder
{
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

        $user = User::create([
            'name' => 'Ahmed Sabry Test',
            'email' => 'ahmaasabry22@gmail.com',
            'phone' => '01212183908',
            'address' => '15 Mohamed Tawfeek',
            'password' => '24001091Km'
        ]);

        // $team = Team::create(['name' => $user->name . 'Team']);
        // $role = Role::where('name', 'superAdmin')->first();

        // $user->addRole($role, $team);
        // $user->syncRoles([$role], $team);

        $nursery = Nurseries::create([
            'name' => 'Ahmed Sabry Test',
            'email' => 'ahmaasabry22@gmail.com',
            'phone' => '01212183908',
            'country' => 'egypt',
            'city' => 'cairo',
            'address' => '15 Mohamed Tawfeek',
            'province' => 'Nasr City',
            'branches_number' => '5',
            'start_fees' => '10000',
            'classes_number' => '100',
            'children_number' => '10',
            'employees_number' => '5',
            'services' => 'services test',
            'about' => 'about test',
            'status' => 'pending',
        ]);

        $team = Team::create(['name' => $user->name . 'Team']);
        $role = Role::where('name', 'nursery_Owner')->first();

        $user->addRole($role, $team);
        $user->syncRoles([$role], $team);
    }
}
