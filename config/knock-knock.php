<?php

return [
    '*' => [
        'enabled' => false, // Default: Enabled for all environments
        'enableCpProtection' => false,
        'loginPath' => 'knock-knock/who-is-there',
        'template' => 'knock-knock',
        'forcedRedirect' => '',
        'password' => getenv('KNOCK_KNOCK_PASSWORD') ?: 'letmein', // Fetch from .env
        'siteSettings' => [],
        'checkInvalidLogins' => false,
        'invalidLoginWindowDuration' => '3600',
        'maxInvalidLogins' => 10,
        'allowIps' => [],
        'denyIps' => [],
        'useRemoteIp' => false,
        'protectedUrls' => [],
        'unprotectedUrls' => [],
    ],
    'staging' => [
        'enabled' => true, // Enabled only in Staging
    ],
    'production' => [
        'enabled' => false, // Explicitly disabled in Production
    ],
];
