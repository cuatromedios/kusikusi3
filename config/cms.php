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
        'langs' => ['en', 'es', 'fr', 'de'], // The first lang will be the default each time the entity is loaded
        'root' => [
            'name' => 'Root',
            'display' => [
                [
                    'component' => 'children',
                    'props' => [
                        'label' => 'Hijos',
                        'allowed' => [],
                        'order'  => 'e.position asc'
                    ]
                ],
                [
                    'component' => 'children',
                    'props' => [
                        'label' => 'Solo contenedores',
                        'allowed' => ['container'],
                        'filter' => ['model:container'],
                        'order'  => 'e.position asc'
                    ]
                ],
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
                        'label' => 'Hijos',
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
                    'component' => 'multiLang'
                ],
                [
                    'component' => 'titleSummaryContent',
                ],
                [
                    'component' => 'textInput',
                    'props' => [
                        'label' => 'multilinea',
                        'field' => 'contents.summary',
                        'params' => [
                            'rows' => 3
                        ]
                    ]
                ],
                [
                    'component' => 'urlAccess'
                ],
                [
                    'component' => 'formHeader',
                    'props' => [ 'text' => 'Publicacion', 'level' => 4 ]
                ],
                [
                    'component' => 'toggleButton',
                    'props' => [
                        'label' => 'Boton boleano',
                        'field' => 'position',
                        'trueValue' => [
                            'label' => 'veinte',
                            'value' => 20
                        ],
                        'falseValue' => [
                            'label' => 'cinco',
                            'value' => 5
                        ],
                    ]
                ],
                [
                    'component' => 'publication'
                ],
                [
                    'component' => 'media',
                    'props' => [
                        'tags' => ['tag1', 'tag2', 'cover', 'icon', 'ejemplo'],
                        'filter' => ['.jpg', '.png']
                    ]
                ],
                [
                    'component' => 'relation',
                    'props' => [
                        'label' => 'Relaciones de medium',
                        'kind' => ['medium','ancestor'],
                        'childrenOf' => 'media',
                        'tags' => ['example', 'ejemplo', 'exemple', 'beispiel']
                    ]
                ]
            ]
        ],
        'section' => [
            'name' => "Section",
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
                        'label' => 'Hijos',
                        'allowed' => ['page'],
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
                    'component' => 'multiLang'
                ],
                [
                    'component' => 'titleSummaryContent',
                ],
                [
                    'component' => 'urlAccess'
                ],
                [
                    'component' => 'formHeader',
                    'props' => [ 'text' => 'TEST', 'level' => 5 ]
                ],
                [
                    'component' => 'publication'
                ],
                [
                    'component' => 'media',
                    'props' => [
                        'tags' => ['tag1', 'tag2', 'tag3', 'tag4'],
                        'filter' => ['.gif']
                    ]
                ]
            ]
        ],
        'page' => [
            'name' => "Page",
            'display' => [
                [
                    'component' => 'entityCard',
                    'props' => [
                        'titleSize' => 2
                    ]
                ]
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
                    'component' => 'multiLang'
                ],
                [
                    'component' => 'titleSummaryContent'
                ],
                [
                    'component' => 'urlAccess'
                ],
                [
                    'component' => 'media',
                    'props' => [
                        'tags' => ['imagen', 'otro']
                    ]
                ],
                [
                    'component' => 'publication'
                ],
                [
                    'component' => 'formHeader',
                    'props' => ['text' => 'H2', 'level' => 2]
                ],
                [
                    'component' => 'formHeader',
                    'props' => ['text' => 'H3', 'level' => 3]
                ],
                [
                    'component' => 'formHeader',
                    'props' => ['text' => 'H4', 'level' => 4]
                ],
            ]
        ]
    ]
];