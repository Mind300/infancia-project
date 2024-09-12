<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superAdmin' => [
            'Roles' => true,
            'Nursery-Approved' => true,
            'Nursery-SetStatus' => true,
            'Nurseries' => true,
            'Payment-History' => true,
            'Admins' => true,
            'Nurseries' => true,
        ],

        'nursery_Owner' => [
            'Nursery-Profile' => true,
            'Manage-Classes' => true,
            'Meal' => true,
            'NewsLetter' => true,
            'Parent-Request' => true,
            'Payment-History' => true,
            'Payment-Request' => true,
            'Nursery-Policy' => true,
            'Roles' => true,
            'Faq' => true,
        ],

        'parent' => [
            'Meal' => true,
            'NewsLetter' => true,
            'Parent-Request' => true,
            'Payment-Request' => true,
            'Nursery-Policy' => true,
            'Faq' => true,
        ],
    ],

    'permissions_map' => [
        'true' => 'access',
        'false' => 'no access',
    ],
];
