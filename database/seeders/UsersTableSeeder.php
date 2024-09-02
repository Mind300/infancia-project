<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'phone' => '01015571129',
            'address' => '15 Mohamed Tawfeek',
            'password' => '24001091Km'
        ]);
        $team = Team::create(['name' => $admin_user->name . 'Team']);
        $role = Role::where('name', 'superAdmin')->first();

        $admin_user->addRole($role, $team);
        $admin_user->syncRoles([$role], $team);
    }
}
