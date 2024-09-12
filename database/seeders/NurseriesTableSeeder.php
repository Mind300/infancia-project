<?php

namespace Database\Seeders;

use App\Laratrust\Models\Team;
use App\Models\Nurseries;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laratrust\Models\Role;

class NurseriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ======================= Nursery Seeder Test ========================= //
        $nursery_user = User::create([
            'name' => 'Kiddy Corrner',
            'email' => 'ahmaasabry22@gmail.com',
            'phone' => '01212183908',
            'address' => '15 Mohamed Tawfeek',
            'password' => '24001091Km'
        ]);

        $nursery = Nurseries::create([
            'name' => 'Kiddy Corrner',
            'email' => 'ahmaasabry22@gmail.com',
            'phone' => '01212183908',
            'country' => 'Egypt',
            'city' => 'Cairo',
            'address' => '15 Mohamed Tawfeek',
            'province' => 'Nasr City',
            'branches_number' => '5',
            'start_fees' => '10000',
            'classes_number' => '100',
            'children_number' => '10',
            'employees_number' => '5',
            'services' => 'Nutritious Meals, Safe Environment',
            'about' => 'At Kiddy Corrner, our ultimate goal is to provide a nurturing a...',
            'status' => 'accepted',
            'user_id' => $nursery_user->id
        ]);

        $team = Team::create(['name' => $nursery->name . 'Team']);
        $role = Role::where('name', 'nursery_Owner')->first();

        $nursery_user->addRole($role, $team);
        $nursery_user->syncRoles([$role], $team);

        // // ABC Nursery
        // $nursery_user = User::create([
        //     'name' => 'ABC Nursery',
        //     'email' => 'abc@gmail.com',
        //     'phone' => '01212183901',
        //     'address' => '10 Hegaz Street',
        //     'password' => '24001091Km'
        // ]);

        // $nursery = Nurseries::create([
        //     'name' => 'ABC Nursery',
        //     'email' => 'abc@gmail.com',
        //     'phone' => '01212183901',
        //     'country' => 'Egypt',
        //     'city' => 'Cairo',
        //     'address' => '10 Hegaz Street',
        //     'province' => 'Masr Gdeda',
        //     'branches_number' => '2',
        //     'start_fees' => '10000',
        //     'classes_number' => '100',
        //     'children_number' => '10',
        //     'employees_number' => '20',
        //     'services' => 'Nutritious Meals, Safe Environment',
        //     'about' => 'At Abc Nursery, our ultimate goal is to provide a nurturing a...',
        //     'status' => 'accepted',
        //     'user_id' => $nursery_user->id
        // ]);

        // $team = Team::create(['name' => $nursery->name . 'Team']);
        // $role = Role::where('name', 'nursery_Owner')->first();

        // $nursery_user->addRole($role, $team);
        // $nursery_user->syncRoles([$role], $team);

        // // Kidy Nursery
        // $nursery_user = User::create([
        //     'name' => 'Kidy Nursery',
        //     'email' => 'kidy@gmail.com',
        //     'phone' => '01212183904',
        //     'address' => '10 Hegaz Street',
        //     'password' => '24001091Km'
        // ]);

        // $nursery = Nurseries::create([
        //     'name' => 'Kidy Nursery',
        //     'email' => 'kidy@gmail.com',
        //     'phone' => '01212183904',
        //     'country' => 'Egypt',
        //     'city' => 'Cairo',
        //     'address' => 'Tagmo3',
        //     'province' => 'Point 90',
        //     'branches_number' => '2',
        //     'start_fees' => '10000',
        //     'classes_number' => '5',
        //     'children_number' => '10',
        //     'employees_number' => '2',
        //     'services' => 'Nutritious Meals, Safe Environment',
        //     'about' => 'At Abc Nursery, our ultimate goal is to provide a nurturing a...',
        //     'status' => 'accepted',
        //     'user_id' => $nursery_user->id
        // ]);

        // $team = Team::create(['name' => $nursery->name . 'Team']);
        // $role = Role::where('name', 'nursery_Owner')->first();

        // $nursery_user->addRole($role, $team);
        // $nursery_user->syncRoles([$role], $team);
    }
}
