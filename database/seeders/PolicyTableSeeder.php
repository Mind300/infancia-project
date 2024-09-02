<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PolicyTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('policies')->insert([
            [
                'title' => 'Information We Collect',
                'description' => 'Personal Information: When you sign up, we collect...',
                'nursery_id' => 1,
            ],
            [
                'title' => 'How We Use Your Information',
                'description' => 'We use the information we collect for the following...',
                'nursery_id' => 1,
            ],
            [
                'title' => 'Sharing Your Information',
                'description' => 'We do not share your personal information with third parties...',
                'nursery_id' => 1,
            ],
            [
                'title' => 'Data Security',
                'description' => 'We take appropriate security measures to protect your data...',
                'nursery_id' => 1,
            ],
            [
                'title' => 'Your Rights',
                'description' => 'You have the right to: Access, update, or delete your information...',
                'nursery_id' => 1,
            ],
            [
                'title' => 'Children\'s Privacy',
                'description' => 'Our services are intended for use by nurseries and not for children...',
                'nursery_id' => 1,
            ],
            [
                'title' => 'Changes to This Privacy Policy',
                'description' => 'We may update this Privacy Policy from time to time...',
                'nursery_id' => 1,
            ],
            [
                'title' => 'Contact Us',
                'description' => 'If you have any questions about this Privacy Policy, please contact us...',
                'nursery_id' => 1,
            ],
        ]);
    }
}
