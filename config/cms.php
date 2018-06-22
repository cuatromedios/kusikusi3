<?php
/*
 * This is general configuration needed for the CMS.
 *
 * WARNING !!!!!
 *
 * THIS INFORMATION IS FOR PUBLIC ACCESS!!! DO NOT SET HERE SENSITIVE INFORMATION LIKE API KEYS OR PASSWORDS
 *
 */
return [
    'models' => [
        'home' => [
            'allowedChildren' => ['section', 'page'],
            'sort' => 'e.position asc'
        ],
        'section' => [
            'allowedChildren' => ['page'],
            'sort' => 'e.position asc'
        ],
        'list' => [
            [
                'label' => 'Root',
                'value' => 'root'
            ],
            [
                'label' => 'Container',
                'value' => 'container'
            ],
            [
                'label' => 'Home',
                'value' => 'home'
            ],
            [
                'label' => 'Section',
                'value' => 'section'
            ],
            [
                'label' => 'User',
                'value' => 'user'
            ],
            [
                'label' => 'Page',
                'value' => 'page'
            ],
            [
                'label' => 'Medium',
                'value' => 'medium'
            ]
        ]
    ]
];