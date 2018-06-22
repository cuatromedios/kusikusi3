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
        ]
    ]
];