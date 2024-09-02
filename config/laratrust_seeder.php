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
            'users' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'nurseries' => 'c,r,u,d',
        ],

        'nursery_Owner' => [
            'employee' => 'c,r,u,d',
            'classes' => 'c,r,u,d',
            'kids' => 'c,r,u,d',
            'followup' => 'c,r,u,d',
            'subject' => 'c,r,u,d',
            'schedule' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'branches' => 'c,r,u,d',
        ],

        'nursery_Admin' => [
            'employee' => 'c,r,u,d',
            'classes' => 'c,r,u,d',
            'kids' => 'c,r,u,d',
            'followup' => 'c,r,u,d',
            'subject' => 'c,r,u,d',
            'schedule' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
        ],
        
        'parent' => [
            'users' => 'r',
        ],

    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
