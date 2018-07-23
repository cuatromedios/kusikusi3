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
        'root' => [
            'name' => 'Root',
            'display' => [
                [
                    'component' => 'children',
                    'props' => [
                        'allowed' => ['section', 'page'],
                        'order'  => 'e.position asc'
                    ]
                ]
            ]
        ],
        'home' => [
            'name' => "Home",
            'display' => [
                [
                    'component' => 'entityCard',
                    'props' => [
                        'titleSize' => 3
                    ]
                ],
                [
                    'component' => 'children',
                    'props' => [
                        'allowed' => ['section', 'page'],
                        'order'  => 'e.position asc'
                    ]
                ],
            ],
            'editor' => [
                [
                    'component' => 'formHeader',
                    'props' => [
                        'field' => 'contents.title',
                        'level' => 3
                    ]
                ],
                [
                    'component' => 'titleSummaryContent',
                ],
                [
                    'component' => 'textInput',
                    'props' => [
                        'label' => 'Dirección de acceso:',
                        'field' => 'contents.url',
                        'params' => [
                            'type' => 'url'
                        ]
                    ]
                ],
                [
                    'component' => 'formHeader',
                    'props' => [ 'text' => 'Publicacion', 'level' => 4 ]
                ],
                [
                    'component' => 'publication'
                ],
                [
                    'component' => 'media'
                ]
            ]
        ],
        'section' => [
            'children' => [
                'allowed' => ['page'],
                'order'  => 'e.position asc'
            ],
            'editor' => [
                'content' => [],
                'info' => [
                    [
                        'label' => 'Titulo:',
                        'field' => 'contents.title',
                        'component' => 'textInput',
                    ],
                    [
                        'label' => 'Dirección de acceso:',
                        'field' => 'contents.url',
                        'component' => 'textInput',
                        'params' => [
                            'type' => 'url'
                        ]
                    ],
                    [
                        'label' => 'Reseña:',
                        'field' => 'contents.summary',
                        'component' => 'textInput',
                        'params' => [
                            'type' => 'textarea',
                            'rows' => '3'
                        ]
                    ],
                    [
                        'label' => 'Descripción:',
                        'field' => 'contents.description',
                        'component' => 'wysiwyg'
                    ]
                ],
                'publication' => [
                    [
                        'label' => 'Posición:',
                        'field' => 'position',
                        'component' => 'textInput',
                        'params' => [
                            'type' => 'number',
                        ]
                    ],
                    [
                        'label' => 'Publicado:',
                        'field' => 'created_at',
                        'component' => 'datetime',
                    ],
                    [
                        'label' => 'Actualizado:',
                        'field' => 'updated_at',
                        'component' => 'datetime'
                    ]
                ],
                'media' => []
            ]
        ],
        'page' => [
            'children' => [
                'allowed' => [],
                'order'  => 'e.position asc'
            ],
            'editor' => [
                'info' => [
                    [
                    'label' => 'Titulo:',
                    'field' => 'contents.title',
                    'component' => 'textInput',
                    ],
                    [
                    'label' => 'Dirección de acceso:',
                    'field' => 'contents.url',
                    'component' => 'textInput',
                    'params' => [
                        'type' => 'url'
                    ]
                    ],
                    [
                    'label' => 'Reseña:',
                    'field' => 'contents.summary',
                    'component' => 'textInput',
                    'params' => [
                        'type' => 'textarea',
                        'rows' => '3'
                    ]
                ],
                    [
                    'label' => 'Descripción:',
                    'field' => 'contents.description',
                    'component' => 'wysiwyg'
                    ]
                ],
                'publication' => [
                [
                    'label' => 'Posición:',
                    'field' => 'position',
                    'component' => 'textInput',
                    'params' => [
                        'type' => 'number',
                    ]
                ],
                [
                    'label' => 'Publicado:',
                    'field' => 'created_at',
                    'component' => 'datetime',
                ],
                [
                    'label' => 'Actualizado:',
                    'field' => 'updated_at',
                    'component' => 'datetime'
                ]
            ],
            'media' => []
            ]
        ]
    ]
];