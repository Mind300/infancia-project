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
        // Create admin User and assign the role to him.

        // $user = User::create([
        //     'name' => 'Khaled Moussa',
        //     'email' => 'khaledmoussa202@gmail.com',
        //     'phone' => '01015571129',
        //     'password' => '24001091Km'
        // ]);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'phone' => '01015571129',
            'password' => '24001091Km'
        ]);

        $team = Team::create(['name' => $user->name . 'Team']);
        $role = Role::where('name', 'superAdmin')->first();

        $user->addRole($role, $team);
        $user->syncRoles([$role], $team);

        // $nursery = Nurseries::create([
        //     'name' => 'Nursery 1',
        //     'email' => 'khaledmoussa202@gmail.com',
        //     'phone' => '01015571129',
        //     'country' => 'egypt',
        //     'city' => 'cairo',
        //     'address' => '15 Mohamed Tawfeek',
        //     'province' => 'Nasr City',
        //     'branches_number' => '5',
        //     'start_fees' => '10000',
        //     'classes_number' => '100',
        //     'children_number' => '10',
        //     'employees_number' => '5',
        //     'services' => 'services lorem',
        //     'about' => 'about lorem',
        // ]);

        // $team = Team::create(['name' => $user->name . 'Team']);
        // $role = Role::where('name', 'nursery_Owner')->first();

        // $user->addRole($role, $team);
        // $user->syncRoles([$role], $team);
    }
}
