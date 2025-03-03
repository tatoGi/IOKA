<?php

return [
    'id' => '4' ,
    'type' => '4',
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
