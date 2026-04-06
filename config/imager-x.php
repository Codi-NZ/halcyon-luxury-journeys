<?php

$defaults = [
    'interlace' => true,
    'jpegQuality' => 80,
    'fillTransforms' => true,
    'fillInterval' => 304,
    'filenamePattern' => '{basename}_{transformString}.{extension}',
    'mode' => 'crop',
    'format' => 'webp',
];

$useLocalAssets = getenv('CRAFT_ENVIRONMENT') === 'dev' || empty(getenv('ASSETS_S3_ACCESS_KEY_ID'));

$localImager = [
    'imagerUrl' => '/assets/resized/',
    'imagerSystemPath' => '@webroot/assets/resized/',
    'fallbackImage' => 'https://images.unsplash.com/photo-1612702044266-4808c864311f',
];

$remoteImager = [
    'imagerUrl' => getenv('ASSETS_BASE_URL') . 'assets/resized/',
    'storages' => ['aws'],
    'storageConfig' => [
        'aws' => [
            'accessKey' => getenv('ASSETS_S3_ACCESS_KEY_ID'),
            'secretAccessKey' => getenv('ASSETS_S3_SECRET_ACCESS_KEY'),
            'region' => getenv('ASSETS_S3_REGION'),
            'bucket' => getenv('ASSETS_S3_BUCKET'),
            'folder' => 'assets/resized',
            'requestHeaders' => array(),
            'storageType' => 'standard',
        ],
    ],
];

return (array_merge(($useLocalAssets ? $localImager : $remoteImager), $defaults));
