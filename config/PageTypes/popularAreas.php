<?php

return [
    'id' => 8 ,
    'type' => 8,
    'name' => 'Popular Areas',
    'sections' => [
        'PoPular' => [
            'label' => 'Popular Areas',
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
    ],


];
