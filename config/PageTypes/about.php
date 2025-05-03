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
                'your_agency' => [
                    'type' => 'text',
                    'label' => 'Your Agency',
                    'required' => true,
                ],
                'your_agency_description' => [
                    'type' => 'textarea',
                    'label' => 'Your Agency Description',
                    'required' => true,
                ],
                'testimonials' => [
                    'type' => 'repeater',
                    'label' => 'Testimonials',
                    'max_items' => 3,
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'label' => 'Name',
                            'required' => true,
                        ],
                        'position' => [
                            'type' => 'text',
                            'label' => 'Position',
                            'required' => true,
                        ],
                        'description' => [
                            'type' => 'textarea',
                            'label' => 'Description',
                            'required' => true,
                        ],
                        'quote' => [
                            'type' => 'textarea',
                            'label' => 'Quote',
                            'required' => true,
                        ],
                        'image' => [
                            'type' => 'image',
                            'label' => 'Image',
                            'required' => true,
                        ],
                        'alt_text' => [
                            'type' => 'text',
                            'label' => 'Image Alt Text',
                            'required' => true,
                        ],
                    ],
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
                    ],
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
                ],
            ],

        ],

    ],
];
