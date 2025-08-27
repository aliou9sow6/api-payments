<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Permissions Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des permissions pour l'application de paiements
    |
    */

    'roles' => [
        'admin' => [
            'name' => 'Administrator',
            'permissions' => [
                'payments.view_all',
                'payments.create',
                'payments.update_all',
                'payments.delete_all',
                'users.view_all',
                'users.create',
                'users.update_all',
                'users.delete_all',
                'recurring_payments.view_all',
                'recurring_payments.create',
                'recurring_payments.update_all',
                'recurring_payments.delete_all',
            ]
        ],
        'user' => [
            'name' => 'Regular User',
            'permissions' => [
                'payments.view_own',
                'payments.create',
                'payments.update_own',
                'payments.delete_own',
                'recurring_payments.view_own',
                'recurring_payments.create',
                'recurring_payments.update_own',
                'recurring_payments.delete_own',
            ]
        ]
    ],

    'payment_statuses' => [
        'pending' => [
            'can_update' => true,
            'can_delete' => true,
        ],
        'completed' => [
            'can_update' => false,
            'can_delete' => false,
        ],
        'failed' => [
            'can_update' => true,
            'can_delete' => true,
        ],
        'refunded' => [
            'can_update' => false,
            'can_delete' => false,
        ]
    ],

    'file_upload' => [
        'max_size' => 2048, // KB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'pdf'],
        'storage_path' => '/storage/app/public/proofs' 
    ]
];