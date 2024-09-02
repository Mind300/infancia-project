<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faqs')->insert([
            [
                'questions' => 'Can I update my personal information?',
                'answer' => 'Yes, you can update your personal information by navigating to the profile settings section.',
                'nursery_id' => 1,
            ],
            [
                'questions' => 'What is the purpose of the dashboard?',
                'answer' => 'The dashboard is designed to help nursery administrators manage and monitor nursery operations.',
                'nursery_id' => 1,
            ],
            [
                'questions' => 'Who can access the nursery dashboard?',
                'answer' => 'Access to the dashboard is restricted to authorized personnel only.',
                'nursery_id' => 1,
            ],
            [
                'questions' => 'How do I add a new user to the dashboard?',
                'answer' => 'Navigate to the "User Management" section, click "Add User", and fill in the required details.',
                'nursery_id' => 1,
            ],
            [
                'questions' => 'Can I assign different roles to users?',
                'answer' => 'Yes, you can assign roles such as Admin, Teacher, and others based on their responsibilities.',
                'nursery_id' => 1,
            ],
            [
                'questions' => 'How can I add a new nursery?',
                'answer' => 'Go to the "Nurseries" section, click "Add Nursery", and fill in the necessary details.',
                'nursery_id' => 1,
            ],
        ]);
    }
}
