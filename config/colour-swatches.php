<?php
/**
 * color-swatches plugin for Craft CMS 5.x.
 *
 * Let clients choose from a predefined set of colours.
 *
 * @link      https://percipio.london
 *
 * @copyright Copyright (c) 2020 Percipio.London
 */

return [
    'palettes' => [
        'Primary' => [
            [
                'label' 	=> 'Red',
                'default' 	=> true,
                'color' 	=> [
                    [
                        'color'     => '#FF0000',
                        'theme'     => 'primary',
                    ],
                ],
            ],
            [
                'label' 	=> 'Green',
                'default' 	=> false,
                'color' 	=> [
                    [
                        'color'     => '#00FF00',
                        'theme'     => 'secondary',
                    ],
                ],
            ],
            [
                'label' 	=> 'Blue',
                'default' 	=> false,
                'color' 	=> [
                    [
                        'color'     => '#0000FF',
                        'theme'     => 'tertiary',
                    ],
                ],
            ],
        ],
    ],
];
