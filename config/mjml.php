<?php
/**
 * MJML Configuration
 * Reference the MJML Craft plugin for more information.
 *
 * @link https://documentation.mjml.io/#configuration
 */

 use craft\helpers\App;

return [
    // The path to where the your version of Node is located, i.e. `/usr/local/bin/node`
    'nodePath'  => App::env('NODE_PATH', '/usr/bin/node'),

    // The path to where the MJML cli installed with npm is located, i.e. `/usr/local/bin/mjml`
    'mjmlCliPath'   => App::env('MJML_PATH', '/usr/bin/mjml'),

    // The app id received by email
    'appId'     => App::env('MJML_APP_ID', null),

    // Enter the secret key received by email
    'secretKey' => App::env('MJML_SECRET_KEY', null),
];