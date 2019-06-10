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
    'langs' => ['en', 'es', 'fr', 'de'], // The first lang will be the default each time the entity is loaded
    'models' => [
        'home' => [
            'name' => "Home",
            'icon' => "home",
            'display' => [
                [
                    'component' => 'EntityHeader'
                ],
                [
                    'component' => 'MediaStrip',
                ],
                [
                    'component' => 'Children',
                    'settings' => [
                        'label' => 'In home:',
                        'allowed' => ['section', 'page'],
                        'order' => 'e.position asc',
                        'tags' => ['menu']
                    ]
                ],
            ],
            'editor' => [
                [
                    'groupName' => 'content.editor.contents',
                    'fields' => [
                        [
                            'label' => 'content.editor.name',
                            'component' => 'TextField',
                            'field' => 'name',
                            'settings' => null
                        ],
                        [
                            'label' => 'content.editor.title',
                            'component' => 'TextField',
                            'field' => 'contents.title',
                            'settings' => [ 'autogrow' => true ]
                        ],
                        [
                            'label' => 'content.editor.summary',
                            'component' => 'TextField',
                            'field' => 'contents.summary',
                            'settings' => [ 'autogrow' => true ]
                        ],
                        [
                            'label' => 'content.editor.contents',
                            'component' => 'TextField',
                            'field' => 'contents.content',
                            'settings' => [ 'type' => 'textarea' ]
                        ]/*,
                        [
                            'label' => 'content.editor.content',
                            'component' => 'MultilangWysiwyg',
                            'field' => 'contents.content',
                            'props' => [ 'lines' => 15 ]
                        ]*/
                    ]
                ]/*,
                [
                    'groupName' => 'content.editor.url',
                    'fields' => [
                        [
                            'label' => 'content.editor.url',
                            'component' => 'url',
                            'field' => 'contents.url',
                            'props' => [ ]
                        ]
                    ]
                ]*/
            ]
        ],
        'page' => [
            'name' => "Page",
            'icon' => "home",
            'display' => [
                [
                    'component' => 'EntityHeader'
                ],
            ],
            'editor' => [
                [
                    'groupName' => 'content.editor.contents',
                    'fields' => [
                        [
                            'label' => 'content.editor.title',
                            'component' => 'TextField',
                            'field' => 'contents.title',
                            'settings' => [ 'autogrow' => true ]
                        ],
                        [
                            'label' => 'content.editor.summary',
                            'component' => 'TextField',
                            'field' => 'contents.summary',
                            'settings' => [ 'autogrow' => true ]
                        ],
                        [
                            'label' => 'content.editor.contents',
                            'component' => 'TextField',
                            'field' => 'contents.content',
                            'settings' => [ 'type' => 'textarea' ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];