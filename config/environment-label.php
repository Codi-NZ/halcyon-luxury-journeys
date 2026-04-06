<?php

$environment = getenv('CRAFT_ENVIRONMENT');

return [
    'showLabel' => in_array($environment, ['dev', 'staging']),
    'labelText' => "Environment: " . ucfirst($environment),
    'labelColor' => ($environment === 'staging') ? '#f39c12' : '#cc5643',
    'textColor' => '#ffffff',
    'targetSelector' => '#nav:before',
];
