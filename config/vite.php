<?php

use craft\helpers\App;

return [

    '*' => [
        'checkDevServer' => false,
        'devServerInternal' => 'http://localhost:3000',
        'devServerPublic' => App::env('CRAFT_DEFAULT_SITE_URL') . ':3000',
        'errorEntry' => 'src/scripts/site.js',
        'manifestPath' => '@webroot/dist/.vite/manifest.json',
        'serverPublic' => App::env('CRAFT_DEFAULT_SITE_URL') . '/dist/',
        'useDevServer' => false,
    ],

    'dev' => [
        'useDevServer' => true,
        'checkDevServer' => true,
    ]
];
