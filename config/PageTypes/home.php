<?php

return [
    'id' => 1,
    'type' => 1,
    'name' => 'Homepage Management',
    'sections' => [
        'section_one' => [
            'label' => 'Section One - Background Slider',
            'fields' => [
                'subtitle' => [
                    'type' => 'text',
                    'label' => 'Small Title (Subtitle)',
                    'required' => true,
                ],
                'title' => [
                    'type' => 'text',
                    'label' => 'Main Title',
                    'required' => true,
                ],
                'slider_images' => [
                    'type' => 'repeater',
                    'label' => 'Slider Images',
                    'fields' => [
                        'image' => [
                            'type' => 'image',
                            'label' => 'Photo',
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
                'title_2' => [
                    'type' => 'text',
                    'label' => 'Title 2',
                    'default' => 'Search for potential matches? from studio apartments to penthouses - select, your layout to see whats available',
                    'required' => true,
                ],
                'slider_images' => [
                    'type' => 'repeater',
                    'label' => 'Image Slider',
                    'min_items' => 1,
                    'max_items' => 3,
                    'fields' => [
                        'image' => [
                            'type' => 'image',
                            'label' => 'Image',
                            'required' => true,
                        ],
                        'url' => [
                            'type' => 'url',
                            'label' => 'Link URL',
                            'required' => true,
                        ],
                    ],
                ],
                'rolling_numbers' => [
                    'type' => 'repeater',
                    'label' => 'Rolling Numbers',
                    'min_items' => 2,
                    'max_items' => 2,
                    'fields' => [
                        'number' => [
                            'type' => 'number',
                            'label' => 'Number',
                            'required' => true,
                        ],
                        'suffix' => [
                            'type' => 'text',
                            'label' => 'Suffix',
                            'required' => true,
                        ],
                        'title' => [
                            'type' => 'text',
                            'label' => 'Title',
                            'required' => true,
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
                                'bulletpoint_add_section' => [
                                    'type' => 'repeater',
                                    'label' => 'Bullet Points Add Section',
                                    'fields' => [
                                        'text' => [
                                            'type' => 'text',
                                            'label' => 'Point Text',
                                            'required' => true,
                                        ],
                                    ],
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
                                        'image' => [
                                            'type' => 'image',
                                            'label' => 'Property Image',
                                            'required' => true,
                                        ],
                                        'title' => [
                                            'type' => 'text',
                                            'label' => 'Property Title',
                                            'default' => 'Address Residences at DHE',
                                            'required' => true,
                                        ],
                                        'subtitle' => [
                                            'type' => 'text',
                                            'label' => 'Property Subtitle',
                                            'default' => '1 - 3 Bedroom spacious Apartment',
                                            'required' => true,
                                        ],
                                        'price_prefix' => [
                                            'type' => 'text',
                                            'label' => 'Value Prefix',
                                            'default' => 'AED',
                                            'required' => true,
                                        ],
                                        'price' => [
                                            'type' => 'text',
                                            'label' => 'Price Value',
                                            'default' => '1,930,000',
                                            'required' => true,
                                            'placeholder' => 'Example: 1,930,000 or 1.930.000',
                                        ],
                                        'bullet_points' => [
                                            'type' => 'repeater',
                                            'label' => 'Bullet Points',
                                            'min_items' => 1,
                                            'fields' => [
                                                'text' => [
                                                    'type' => 'text',
                                                    'label' => 'Point Text',
                                                    'required' => true,
                                                ],
                                            ],
                                        ],
                                        'url' => [
                                            'type' => 'url',
                                            'label' => 'See More URL',
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
                                'bulletpoint_add_section' => [
                                    'type' => 'repeater',
                                    'label' => 'Bullet Points Add Section',
                                    'fields' => [
                                        'text' => [
                                            'type' => 'text',
                                            'label' => 'Point Text',
                                            'required' => true,
                                        ],
                                    ],
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
                                        'image' => [
                                            'type' => 'image',
                                            'label' => 'Property Image',
                                            'required' => true,
                                        ],
                                        'title' => [
                                            'type' => 'text',
                                            'label' => 'Property Title',
                                            'required' => true,
                                        ],
                                        'subtitle' => [
                                            'type' => 'text',
                                            'label' => 'Property Subtitle',
                                            'required' => true,
                                        ],
                                        'price_prefix' => [
                                            'type' => 'text',
                                            'label' => 'Value Prefix',
                                            'default' => 'AED',
                                            'required' => true,
                                        ],
                                        'price' => [
                                            'type' => 'text',
                                            'label' => 'Price Value',
                                            'required' => true,
                                            'placeholder' => 'Example: 1,930,000 or 1.930.000',
                                        ],
                                        'bullet_points' => [
                                            'type' => 'repeater',
                                            'label' => 'Bullet Points',
                                            'min_items' => 1,
                                            'fields' => [
                                                'text' => [
                                                    'type' => 'text',
                                                    'label' => 'Point Text',
                                                    'required' => true,
                                                ],
                                            ],
                                        ],
                                        'url' => [
                                            'type' => 'url',
                                            'label' => 'See More URL',
                                            'required' => true,
                                        ],
                                    ],
                                ],
                                'image_or_like_this' => [
                                    'type' => 'photo',
                                    'label' => 'Image or Like This',
                                    'required' => true,
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
                                'bulletpoint_add_section' => [
                                    'type' => 'repeater',
                                    'label' => 'Bullet Points Add Section',
                                    'fields' => [
                                        'text' => [
                                            'type' => 'text',
                                            'label' => 'Point Text',
                                            'required' => true,
                                        ],
                                    ],
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
                                        'image' => [
                                            'type' => 'image',
                                            'label' => 'Property Image',
                                            'required' => true,
                                        ],
                                        'title' => [
                                            'type' => 'text',
                                            'label' => 'Property Title',
                                            'required' => true,
                                        ],
                                        'subtitle' => [
                                            'type' => 'text',
                                            'label' => 'Property Subtitle',
                                            'required' => true,
                                        ],
                                        'price_prefix' => [
                                            'type' => 'text',
                                            'label' => 'Value Prefix',
                                            'default' => 'AED',
                                            'required' => true,
                                        ],
                                        'price' => [
                                            'type' => 'text',
                                            'label' => 'Price Value',
                                            'required' => true,
                                            'placeholder' => 'Example: 1,930,000 or 1.930.000',
                                        ],
                                        'bullet_points' => [
                                            'type' => 'repeater',
                                            'label' => 'Bullet Points',
                                            'min_items' => 1,
                                            'fields' => [
                                                'text' => [
                                                    'type' => 'text',
                                                    'label' => 'Point Text',
                                                    'required' => true,
                                                ],
                                            ],
                                        ],
                                        'url' => [
                                            'type' => 'url',
                                            'label' => 'See More URL',
                                            'required' => true,
                                        ],
                                    ],
                                ],
                                'image_or_like_this' => [
                                    'type' => 'image_or_like_this',
                                    'label' => 'Image or Like This',
                                    'required' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                'image_or_like_this' => [
                    'type' => 'image_or_like_this',
                    'label' => 'Image or Like This',
                    'required' => true,
                ],
            ],
        ],
        'section_four' => [
            'label' => 'Section Four - Popular Areas',
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
            ],
        ],
        'section_five' => [
            'label' => 'Section Five - Our Team',
            'fields' => [
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
                'image_or_like_this' => [
                    'type' => 'image_or_like_this',
                    'label' => 'Image or Like This',
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
    ],
];
