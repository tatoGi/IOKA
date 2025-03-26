<?php

return [
    'id' => 3,
    'type' => 3,
    'name' => 'Contact',
    'sections' => [
        'Add_Contact_Section' => [
            'label' => 'Add Contact Section',
            'fields' => [
                'subtitle' => [
                    'type' => 'text',
                    'label' => 'Small Title (Subtitle)',
                    'required' => true,
                ],
                'title' => [
                    'type' => 'text',
                    'label' => 'Title',
                    'required' => true,
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Description',
                    'required' => true,
                ],
                'phone_numbers' => [
                    'type' => 'repeater',
                    'label' => 'Phone Numbers',
                    'fields' => [
                        'phone' => [
                            'type' => 'text',
                            'label' => 'Phone Number',
                            'required' => true,
                        ],
                        'label' => [
                            'type' => 'text',
                            'label' => 'Label (e.g., Main Office, Support)',
                            'required' => false,
                        ],
                    ],
                ],
                'email_addresses' => [
                    'type' => 'repeater',
                    'label' => 'Email Addresses',
                    'fields' => [
                        'email' => [
                            'type' => 'email',
                            'label' => 'Email Address',
                            'required' => true,
                        ],
                        'label' => [
                            'type' => 'text',
                            'label' => 'Label (e.g., Info, Support)',
                            'required' => false,
                        ],
                    ],
                ],
                'locations' => [
                    'type' => 'repeater',
                    'label' => 'Locations',
                    'fields' => [
                        'address' => [
                            'type' => 'text',
                            'label' => 'Address',
                            'required' => true,
                        ],
                        'google_maps_link' => [
                            'type' => 'text',
                            'label' => 'Google Maps Link',
                            'required' => true,
                        ],
                        'label' => [
                            'type' => 'text',
                            'label' => 'Label (e.g., Main Office, Branch)',
                            'required' => false,
                        ],
                    ],
                ],
            ],
        ],

    ],
];
