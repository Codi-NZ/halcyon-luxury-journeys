<?php

/**
 * Custom Configuration
 */

use Craft;

return [
    '*' => [
        /**
         * Eager loading
         */
        'eager' => new class
        {
            public $contentBlocks = [
                'form:form',
                'image:image',
                'imageGallery:images',
                'video:image',
                'video:video',
                'video:videoModal',
            ];
            public $sectionBlocks = [
                'form:form',
                'image:image',
                'imageGallery:images',
                'video:image',
                'video:video',
                'video:videoModal',
            ];
            public $events = [
                'image',
                'video',
                // 'categories', // TODO: REVIEW -- blocking event card entry
            ];
            public $news = [
                'image',
                'video',
                'categories',
            ];
            public $people = [
                'image',
                // 'categories', // TODO: REVIEW -- blocking person card entry
            ];
        },
    ],
];
