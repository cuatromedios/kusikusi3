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
    'langs' => ['en'], // The first lang will be the default each time the entity is loaded
    'models' => [
        'home' => [
            'name' => "Home",
            'icon' => "home",
            'display' => [
                [
                    'component' => 'EntityHeader',
                    'props' => [
                        'summary_field' => 'contents.subtitle'
                    ]
                ],[
                    'component' => 'MediaGrid',
                    'props' => [
                        'tags' => ['hero', 'slider', 'background']
                    ]
                ],
                [
                    'component' => 'Children',
                    'props' => [
                        'label' => 'In home:',
                        'allowed' => ['section', 'page'],
                        'order' => 'e.position asc',
                        'tags' => ['menu']
                    ]
                ]
            ],
            'editor' => [
                [
                    'groupName' => 'content.editor.contents',
                    'fields' => [
                        [
                            'label' => 'content.editor.title',
                            'component' => 'TextField',
                            'field' => 'contents.title',
                            'props' => [ 'autogrow' => true ]
                        ],
                        [
                            'label' => 'content.editor.summary',
                            'component' => 'TextField',
                            'field' => 'contents.summary',
                            'props' => [ 'autogrow' => true ]
                        ],
                        [
                            'label' => 'content.editor.contents',
                            'component' => 'HtmlField',
                            'field' => 'contents.content',
                            'props' => [ 'autogrow' => true ]
                        ]
                    ]
                ]
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
                            'props' => [ 'autogrow' => true ]
                        ],
                        [
                            'label' => 'content.editor.summary',
                            'component' => 'TextField',
                            'field' => 'contents.summary',
                            'props' => [ 'autogrow' => true ]
                        ],
                        [
                            'label' => 'content.editor.contents',
                            'component' => 'TextField',
                            'field' => 'contents.content',
                            'props' => [ 'autogrow' => true ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];