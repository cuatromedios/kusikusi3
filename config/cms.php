<?php
/*
 * This is general configuration needed for the CMS.
 *
 * WARNING !!!!!
 *
 * THIS INFORMATION IS FOR PUBLIC ACCESS!!! DO NOT SET HERE SENSITIVE INFORMATION LIKE API KEYS OR PASSWORDS
 *
 */

/*
 * The components to be used to display information
 */

/**
 * @param  string  $summaryField Define the field that will be used as a summary in the header component
 * @return array
 */
$header = function($summaryField = 'contents.summary') {
    return [
        'component' => 'EntityHeader',
        'props' => [
            'summary_field' => $summaryField
        ]
    ];
};

/**
 * @param  string  $label The label to show in the component as a header
 * @param  array  $tags The array tags that can be used to -tag- the media files
 * @return array
 */
$mediaGrid = function($label='Media', $tags = []) {
    return [
        'component' => 'MediaGrid',
        'props' => [
            'label' => $label,
            'tags' => $tags
        ]
    ];
};

/**
 * @param  string  $label The label to show in the component as a header
 * @param  array  $allowed The list of models that can be added as children
 * @param  array  $tags The array of tags can be used to -tag- the children
 * @param  string  $order The order the children must be sorted
 * @return array
 */
$children = function($label='In this section', $allowed=[], $tags=[], $order='e.position asc') {
    return [
        'component' => 'Children',
        'props' => [
            'label' => $label,
            'allowed' => $allowed,
            'order' =>  $order,
            'tags' => $tags
        ]
    ];
};


/*
 * Defining field types to be used when editing, related to fields supported by the front end:
 * TextField, HtmlField, UrlField, DatetimeField and BooleanField
 */
$textField = function($label='Text', $field='contents.title', $autogrow=true) {
    return [
        'label' => $label,
        'component' => 'TextField',
        'field' => $field,
        'props' => [
            'autogrow' => $autogrow
        ]
    ];
};
$urlField = function($label='Url in the website') {
    return [
        'label' => $label,
        'component' => 'UrlField',
        'field' => 'contents.url',
        'props' => [
            'autogrow' => false
        ]
    ];
};
$htmlField = function($label='Text', $field='contents.title', $autogrow=true) {
    return [
        'label' => $label,
        'component' => 'HtmlField',
        'field' => $field,
        'props' => [
            'autogrow' => $autogrow
        ]
    ];
};
$booleanField = function($label='Active', $field='active') {
    return [
        'label' => $label,
        'component' => 'BooleanField',
        'field' => $field
    ];
};
$datetimeField = function($label='Date', $field='publicated_at') {
    return [
        'label' => $label,
        'component' => 'DatetimeField',
        'field' => $field
    ];
};

/*
 * Use previous editor fields to define how to use them to edit entity's properties
 */
$title = $textField('content.editor.title', 'contents.title', true);
$summary = $textField('content.editor.summary', 'contents.summary', true);
$content = $htmlField('content.editor.contents', 'contents.content', true);
$active = $booleanField('content.editor.active', 'active');
$publicated_at = $datetimeField('content.editor.published_at', 'publicated_at');

/*
 * Groups of fields to be used
 */
$contentGroup = [
    'groupName' => 'content.editor.contents',
    'fields' => [$title, $summary, $content]
];
$publicationGroup = [
    'groupName' => 'content.editor.publication',
    'fields' => [$publicated_at, $active]
];


/*
 * Return the configuration structure
 * Icons are the name of the Material Design Icons https://material.io/tools/icons/?style=baseline
 */

return [
    'langs' => ['en'], // The first lang will be the default each time the entity is loaded
    'models' => [
        'home' => [
            'name' => "Home",
            'icon' => "home",
            'display' => [
                $header(),
                $children('In home', ['section', 'page'], ['menu']),
                $mediaGrid('Home images', ['hero', 'slider', 'background'])
            ],
            'editor' => [
                $contentGroup
            ]
        ],
        'page' => [
            'name' => "Page",
            'icon' => "description",
            'display' => [
                $header(),
                $mediaGrid('Media', ['hero', 'slider'])
            ],
            'editor' => [
                $contentGroup,
                $publicationGroup
            ]
        ],
        'section' => [
            'name' => "Section",
            'icon' => "folder",
            'display' => [
                $header(),
                $children('In this section', ['section', 'page'], []),
                $mediaGrid('Media', ['hero', 'slider'])
            ],
            'editor' => [
                $contentGroup,
                $publicationGroup
            ]
        ],
        'default' => [
            'name' => "Container",
            'icon' => "inbox",
            'display' => [
                $children('Stored here', [], [])
            ]
        ]
    ]
];