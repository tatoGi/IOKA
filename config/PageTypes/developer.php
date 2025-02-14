<?php

return [
    'id' => 7,
    'type' => 7,
    'name' => 'Developer',
    'sections' => [
        'developer' => [
            'label' => 'Developer',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Developer Name',
                    'required' => true,
        ],
        'paragraph' => [
            'type' => 'group',
            'label' => 'Paragraph',
            'fields' => [
                'title' => [
                    'type' => 'textarea',
                    'label' => 'paragraph',
                    'required' => true,
                    'value' => '',
                ],
            ],
        ],
        'contact_info' => [
            'type' => 'group',
            'label' => 'Contact Information',
            'fields' => [
                'phone' => [
                    'type' => 'text',
                    'label' => 'Phone Number',
                    'required' => true,
                    'placeholder' => '+971 XX XXX XXXX'
                ],
                'whatsapp' => [
                    'type' => 'text',
                    'label' => 'WhatsApp Number',
                    'required' => false,
                    'placeholder' => '+971 XX XXX XXXX'
                ],
            ]
        ],
        'awards' => [
            'type' => 'repeater',
            'label' => 'Awards',
            'fields' => [
                'award_title' => [
                    'type' => 'text',
                    'label' => 'Award Title',
                    'required' => true,
                ],
                'award_year' => [
                    'type' => 'text',
                    'label' => 'Year Received',
                    'required' => false,
                ],
                'award_description' => [
                    'type' => 'textarea',
                    'label' => 'Award Description',
                    'required' => false,
                ],
            ]
        ],
        ],
    ],
],
];
