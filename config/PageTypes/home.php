<?php

return [
    'id' => 1,
    'type' => 1,
    'name' => 'Homepage Management',
    'sections' => [
        'section_one' => [
            'label' => 'Section One - Background Slider',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Title',
                    'required' => true,
                ],
                'slider_images' => [
                    'type' => 'repeater',
                    'label' => 'Slider Images',
                    'fields' => [
                        'image' => [
                            'type' => 'image',
                            'label' => 'Photo',
                            'required' => false,
                        ],
                        'mobile_image' => [
                            'type' => 'mobile_image',
                            'label' => 'Mobile Photo',
                            'required' => false,
                        ],
                        'title' => [
                            'type' => 'text',
                            'label' => 'Image Title',
                            'required' => true,
                        ],
                        'description' => [
                            'type' => 'text',
                            'label' => 'Image Description',
                            'required' => true,
                        ],
                        'alt_text' => [
                            'type' => 'text',
                            'label' => 'Image Alt Text',
                            'required' => true,
                        ],
                    ],
                ],

            ],
        ],
        'section_two' => [
            'label' => 'Section Two - Live Invest Grow',
            'fields' => [
                'subtitle' => [
                    'type' => 'text',
                    'label' => 'Subtitle',
                    'default' => 'Live Invest Grow',
                    'required' => true,
                ],
                'title' => [
                    'type' => 'text',
                    'label' => 'Title 1',
                    'default' => 'Dubai - Your partner for accelerated growth',
                    'required' => true,
                ],
                'description' => [
                    'type' => 'description',
                    'label' => 'Description',
                    'default' => 'Search for potential matches? from studio apartments to penthouses - select, your layout to see whats available',
                    'required' => true,
                ],
                'slider_images' => [
                    'type' => 'repeater',
                    'label' => 'Image Slider',
                    'fields' => [
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
                        'url' => [
                            'type' => 'url',
                            'label' => 'Link URL',
                            'required' => true,
                        ],
                        'mobile_image' => [
                            'type' => 'mobile_image',
                            'label' => 'Mobile Image',
                            'required' => false,
                        ],
                    ],
                ],
                'rolling_numbers' => [
                    'type' => 'repeater',
                    'label' => 'Rolling Numbers',
                    'fields' => [
                        'number' => [
                            'type' => 'number',
                            'label' => 'Number',
                        ],
                        'suffix' => [
                            'type' => 'text',
                            'label' => 'Suffix',
                            'wrapper' => false
                        ],
                        'title' => [
                            'type' => 'text',
                            'label' => 'Title',
                            'wrapper' => false
                        ],
                    ],
                ],

            ],
        ],
        'section_three' => [
            'label' => 'Section Three - New Properties',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Section Title',
                    'default' => 'New properties in DUBAI',
                    'required' => true,
                ],
                'tabs' => [
                    'type' => 'tabs',
                    'label' => 'Property Types',
                    'tabs' => [
                        'offplan' => [
                            'label' => 'Offplan',
                            'fields' => [
                                'image_field' => [
                                    'type' => 'image',
                                    'label' => 'Image Field',
                                    'required' => true,
                                ],
                                'mobile_image' => [
                                    'type' => 'mobile_image',
                                    'label' => 'Mobile Photo',
                                    'required' => false,
                                ],
                                'alt_text' => [
                                    'type' => 'text',
                                    'label' => 'Image Alt Text',
                                    'required' => false,
                                    'attributes' => [
                                        'placeholder' => 'Enter descriptive text for the image',
                                        'tabindex' => '0'
                                    ],
                                    'conditions' => [
                                        'image_field' => [
                                            'type' => 'not_empty'
                                        ]
                                    ]
                                ],
                                'title_one' => [
                                    'type' => 'text',
                                    'label' => 'Title One',
                                    'default' => 'Title One Example',
                                    'required' => true,
                                ],
                                'title_two' => [
                                    'type' => 'text',
                                    'label' => 'Title Two',
                                    'default' => 'Title Two Example',
                                    'required' => true,
                                ],
                                'number' => [
                                    'type' => 'number',
                                    'label' => 'Number',
                                    'required' => true,
                                ],
                                'number_suffix' => [
                                    'type' => 'text',
                                    'label' => 'Number Suffix',
                                    'default' => 'Units',
                                ],

                                'url' => [
                                    'type' => 'url',
                                    'label' => 'See More URL',
                                    'required' => true,
                                ],
                                'properties' => [
                                    'type' => 'repeater',
                                    'label' => 'Properties',
                                    'fields' => [
                                        'title' => [
                                            'type' => 'text',
                                            'label' => 'Title',
                                            'required' => true,
                                        ],
                                    ],
                                ],

                            ],
                        ],
                        'resale' => [
                            'label' => 'Resale',
                            'fields' => [
                                'image_field' => [
                                    'type' => 'image',
                                    'label' => 'Image Field',
                                    'required' => true,
                                ],
                                'mobile_image_field' => [
                                    'type' => 'mobile_image',
                                    'label' => 'Mobile Image Field',
                                    'required' => false,
                                ],
                                'alt_text' => [
                                    'type' => 'text',
                                    'label' => 'Image Alt Text',
                                    'required' => false,
                                    'attributes' => [
                                        'placeholder' => 'Enter descriptive text for the image',
                                        'tabindex' => '0'
                                    ],
                                    'conditions' => [
                                        'image_field' => [
                                            'type' => 'not_empty'
                                        ]
                                    ]
                                ],
                                'title_one' => [
                                    'type' => 'text',
                                    'label' => 'Title One',
                                    'default' => 'Title One Example',
                                    'required' => true,
                                ],
                                'title_two' => [
                                    'type' => 'text',
                                    'label' => 'Title Two',
                                    'default' => 'Title Two Example',
                                    'required' => true,
                                ],
                                'number' => [
                                    'type' => 'number',
                                    'label' => 'Number',
                                    'required' => true,
                                ],
                                'number_suffix' => [
                                    'type' => 'text',
                                    'label' => 'Number Suffix',
                                    'default' => 'Units',
                                ],

                                'url' => [
                                    'type' => 'url',
                                    'label' => 'See More URL',
                                    'required' => true,
                                ],
                              'properties' => [
                                    'type' => 'repeater',
                                    'label' => 'Properties',
                                    'fields' => [
                                        'title' => [
                                            'type' => 'text',
                                            'label' => 'Title',
                                            'required' => true,
                                        ],
                                    ],
                                ],

                            ],
                        ],
                        'rental' => [
                            'label' => 'Rental',
                            'fields' => [
                                'image_field' => [
                                    'type' => 'image',
                                    'label' => 'Image Field',
                                    'required' => true,
                                ],
                                'mobile_image_field' => [
                                    'type' => 'mobile_image',
                                    'label' => 'Mobile Image Field',
                                    'required' => false,
                                ],
                                'alt_text' => [
                                    'type' => 'text',
                                    'label' => 'Image Alt Text',
                                    'required' => false,
                                    'attributes' => [
                                        'placeholder' => 'Enter descriptive text for the image',
                                        'tabindex' => '0'
                                    ],
                                    'conditions' => [
                                        'image_field' => [
                                            'type' => 'not_empty'
                                        ]
                                    ]
                                ],
                                'title_one' => [
                                    'type' => 'text',
                                    'label' => 'Title One',
                                    'default' => 'Title One Example',
                                    'required' => true,
                                ],
                                'title_two' => [
                                    'type' => 'text',
                                    'label' => 'Title Two',
                                    'default' => 'Title Two Example',
                                    'required' => true,
                                ],
                                'number' => [
                                    'type' => 'number',
                                    'label' => 'Number',
                                    'required' => true,
                                ],
                                'number_suffix' => [
                                    'type' => 'text',
                                    'label' => 'Number Suffix',
                                    'default' => 'Units',
                                ],

                                'url' => [
                                    'type' => 'url',
                                    'label' => 'See More URL',
                                    'required' => true,
                                ],
                               'properties' => [
                                    'type' => 'repeater',
                                    'label' => 'Properties',
                                    'fields' => [
                                        'title' => [
                                            'type' => 'text',
                                            'label' => 'Title',
                                            'required' => true,
                                        ],
                                    ],
                                ],

                            ],
                        ],
                    ],
                ],

            ],
        ],
       'section_four' => [
    'label' => 'Section Four - Popular Areas',
    'fields' => [
        'title' => [
            'type' => 'text',
            'label' => 'Main Title',
            'required' => true,
        ],
        'Add_Popular_Areas' => [
            'type' => 'repeater',
            'label' => 'Popular Areas',
            'min_items' => 1,
            'max_items' => 4,
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Title',
                    'required' => true,
                ],
                'redirect_link' => [
                    'type' => 'text',
                    'label' => 'Redirect Link',
                    'required' => true,
                ],
                'image' => [
                    'type' => 'image',
                    'label' => 'Image',
                    'required' => true,
                ],
                'mobile_image' => [
                    'type' => 'mobile_image',
                    'label' => 'Mobile Image',
                    'required' => false,
                ],
                'alt_text' => [
                    'type' => 'text',
                    'label' => 'Image Alt Text',
                    'required' => true,
                ],
                'property_types' => [
                    'type' => 'select',
                    'label' => 'Property Types',
                    'required' => true,
                    'multiple' => true,
                    'class' => 'select2',
                    'options' => [
                        'offplan' => 'Offplan',
                        'rental' => 'Rental',
                        'resale' => 'Resale'
                    ],
                    'attributes' => [
                        'placeholder' => 'Select property types...'
                    ]
                ]
            ]
        ],
    ],
],
        'section_five' => [
            'label' => 'Section Five - Our Team',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Main Title',
                    'required' => true,
                ],
                'team_members' => [
                    'type' => 'repeater',
                    'label' => 'Team Members',
                    'min_items' => 1,
                    'max_items' => 4,
                    'fields' => [
                        'image' => [
                            'type' => 'image',
                            'label' => 'Team Member Photo',
                            'required' => true,
                        ],
                        'mobile_image' => [
                            'type' => 'mobile_image',
                            'label' => 'Mobile Team Member Photo',
                            'required' => false,
                        ],
                        'alt_text' => [
                            'type' => 'text',
                            'label' => 'Image Alt Text',
                            'required' => true,
                        ],
                        'title' => [
                            'type' => 'text',
                            'label' => 'Title',
                            'required' => true,
                            'placeholder' => 'Enter team member name/title',
                        ],
                        'subtitle_1' => [
                            'type' => 'text',
                            'label' => 'Subtitle 1',
                            'required' => true,
                            'placeholder' => 'Enter first subtitle (e.g. position)',
                        ],
                        'subtitle_2' => [
                            'type' => 'text',
                            'label' => 'Subtitle 2',
                            'required' => true,
                            'placeholder' => 'Enter second subtitle',
                        ],
                    ],
                ],
                'Redirect_Link' => [
                    'type' => 'text',
                    'label' => 'Redirect Link',
                    'required' => true,
                ],
            ],
        ],
        'section_six' => [
            'label' => 'Section Six - Testimonials',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Section Title',
                    'default' => 'What our clients say',
                    'required' => true,
                ],
                'subtitle' => [
                    'type' => 'text',
                    'label' => 'Section Subtitle',
                    'required' => true,
                ],
                'google_reviews' => [
                    'label' => 'Google Reviews Section',
                    'type' => 'group',
                    'fields' => [
                        'value' => [
                            'type' => 'number',
                            'label' => 'Reviews Count',
                            'default' => '250',
                            'required' => true,
                        ],
                        'prefix' => [
                            'type' => 'text',
                            'label' => 'Value Prefix',
                            'default' => '+',
                            'required' => true,
                        ],
                        'title' => [
                            'type' => 'text',
                            'label' => 'Reviews Title',
                            'default' => 'google reviews',
                            'required' => true,
                        ],
                    ],
                ],
                'rated_text' => [
                    'type' => 'text',
                    'label' => 'Rated Text',
                    'required' => true,
                ],
                'testimonials' => [
                    'type' => 'repeater',
                    'label' => 'Client Testimonials',
                    'fields' => [
                        'photo' => [
                            'type' => 'image',
                            'label' => 'Client Photo',
                            'required' => true,
                        ],
                        'alt_text' => [
                            'type' => 'text',
                            'label' => 'Image Alt Text',
                            'required' => true,
                        ],
                        'name' => [
                            'type' => 'text',
                            'label' => 'Client Name',
                            'required' => true,
                        ],
                        'rating' => [
                            'type' => 'number',
                            'label' => 'Star Rating (1-5)',
                            'required' => true,
                            'min' => 1,
                            'max' => 5,
                            'step' => 0.5,
                            'placeholder' => 'Enter rating between 1 and 5 (e.g. 4.5)',
                        ],
                        'body' => [
                            'type' => 'textarea',
                            'label' => 'Testimonial Text',
                            'required' => true,
                        ],
                    ],
                ],

            ],
        ],

    ]
];
