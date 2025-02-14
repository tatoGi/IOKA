<?php

return [
    'id' => 2,
    'type' => 2,
    'name' => 'About Us',
    'sections' => [
        'about' => [
            'label' => 'About',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Title',
                    'required' => true,
                ],
                'number_boxes' => [
                    'type' => 'repeater',
                    'label' => 'Number Boxes',
                    'max_items' => 4,
                    'fields' => [
                        'number' => [
                            'type' => 'number',
                            'label' => 'Number',
                            'required' => true,
                            'step' => '0.01', // Allows float numbers
                        ],
                        'suffix' => [
                            'type' => 'text',
                            'label' => 'Suffix (e.g. +, $, %)',
                            'required' => false,
                        ],
                        'title' => [
                            'type' => 'text',
                            'label' => 'Title',
                            'required' => true,
                        ],
                    ]
                ],
                'paragraph' => [
                    'type' => 'group',
                    'label' => 'Paragraph',
                    'fields' => [
                        'title' => [
                            'type' => 'textarea',
                            'label' => 'paragraph',
                            'required' => true,
                            'value' => 'As a brokerage rooted in one of the world\'s most iconic cities, we pride ourselves on',
                        ],
                    ],
                ]
            ]

        ],

    ],
];
