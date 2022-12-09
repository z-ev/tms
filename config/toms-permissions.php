<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

use App\Models\User;

if (!defined( 'PERMISSIONS_LIST_ALL')) {
    define('PERMISSIONS_LIST_ALL',
        [
            'index' => 'all',
            'store' => 'all',
            'show' => 'all',
            'update' => 'all',
            'destroy' => 'all',
            'showRelationship' => 'all',
            'updateRelationship' => 'all',
            'attachRelationship' => 'all',
            'detachRelationship' => 'all',
            'showRelated' => 'all',
            'updateStatus' => 'all',
        ]);
}

if (!defined( 'PERMISSIONS_LIST_OWN')) {
    define('PERMISSIONS_LIST_OWN',
        [
            'index' => 'own',
            'store' => 'own',
            'show' => 'own',
            'update' => 'own',
            'destroy' => 'own',
            'showRelationship' => 'own',
            'updateRelationship' => 'own',
            'attachRelationship' => 'own',
            'detachRelationship' => 'own',
            'showRelated' => 'own',
            'updateStatus' => 'own',
        ]);
}

return [
    User::ROLE_ADMIN => [
        'weight' => 1,
        'throttle' => [
            'maxAttempts' => 100,
            'decayMinutes' => 1,
        ],
        'permissions' => [
            'users' => PERMISSIONS_LIST_ALL,
            'merchants' => PERMISSIONS_LIST_ALL,
            'orders' => PERMISSIONS_LIST_ALL,
            'statusHistories' => PERMISSIONS_LIST_ALL,
        ]
    ],
    User::ROLE_MERCHANT => [
        'weight' => 2,
        'throttle' => [
            'maxAttempts' => 40,
            'decayMinutes' => 1,
        ],
        'permissions' => [
            'orders' => PERMISSIONS_LIST_OWN,
            'statusHistories' => PERMISSIONS_LIST_OWN,
        ]
    ],
    User::ROLE_OPERATOR => [
        'weight' => 3,
        'throttle' => [
            'maxAttempts' => 30,
            'decayMinutes' => 1,
        ],
        'permissions' => [
            'orders' => [
                'index' => 'own',
                'show' => 'own',
                'update' => 'own',
                'showRelationship' => 'own',
                'showRelated' => 'own',
                'updateStatus' => 'own',
            ],
        ]
    ],
    User::ROLE_CLIENT => [
        'weight' => 4,
        'throttle' => [
            'maxAttempts' => 20,
            'decayMinutes' => 1,
        ],
        'permissions' => [
            'orders' => [
                'index' => 'own',
                'show' => 'own',
                'showRelationship' => 'own',
                'showRelated' => 'own',
            ],
        ]
    ],
    User::ROLE_GUEST => [
        'weight' => 5,
        'throttle' => [
            'maxAttempts' => 10,
            'decayMinutes' => 1,
        ],
        'permissions' => [],
    ],
];
