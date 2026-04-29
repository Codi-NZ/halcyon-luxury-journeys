<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 */

use craft\config\GeneralConfig;
use craft\helpers\App;

// Basic environment flags for use in config below
$isProduction = App::env('CRAFT_ENVIRONMENT') === 'production' || App::env('CRAFT_ENVIRONMENT') === 'live';
$isDev = App::env('CRAFT_ENVIRONMENT') === 'dev';

// Allow dev mode to be overridden by .env variable, but default to enabled in dev environment
$enableDevMode = (null !== App::env('CRAFT_DEV_MODE'))
    ? App::env('CRAFT_DEV_MODE')
    : $isDev
;

// Assets Base URL
$assetsBaseUrl = App::env('ASSETS_BASE_URL') === '/'
    ? App::env('ASSETS_BASE_URL') . App::env('ASSETS_BASE_PATH')
    : App::env('ASSETS_BASE_URL');

return GeneralConfig::create()
    ->isSystemLive(App::env('IS_SYSTEM_LIVE') ?? true)
    // Allow administrative changes (we prevent admin changes unless we're in the dev environment)
    ->allowAdminChanges($isDev)
    // The URI segment Craft should look for when determining if the current request should route to the control panel rather than the front-end website.
    ->cpTrigger(App::env('CP_TRIGGER') ?? 'admin')
    // The default language the control panel should use for users who haven’t set a preferred language yet.
    ->defaultCpLanguage("en-AU")
    // Set the default week start day for date pickers (0 = Sunday, 1 = Monday, etc.)
    ->defaultWeekStartDay(1)
    // Allow system and plugin updates from the control panel (This setting will automatically be disabled if allowAdminChanges is disabled.)
    ->allowUpdates(App::env('ALLOW_UPDATES') ?? true)
    // Enable Dev Mode (see https://craftcms.com/guides/what-dev-mode-does)
    ->devMode($enableDevMode)
    // Disallow robots (we block robots on non-production environments)
    ->disallowRobots($isProduction === false)
    // Whether the GraphQL API should be enabled.
    ->enableGql(false)
    // If set in the ENV, all emails will be sent to this address.
    ->testToEmailAddress(App::env('TEST_TO_EMAIL_ADDRESS') ?? false)
    // The timezone Craft should use when formatting dates.
    ->timezone(App::env('TIMEZONE') ?? 'Australia/Adelaide')
    // The prefix for error templates.
    ->errorTemplatePrefix('_exceptions/')
    // The path to the image that should be used when a broken image is encountered.
    ->brokenImagePath('@webroot/dist/images/fallback.png')
    // Generate image transforms synchronously during page render so URLs resolve
    // to real files instead of /actions/assets/generate-transform (avoids broken
    // images when the queue/action endpoint isn't servicing requests reliably).
    ->generateTransformsBeforePageLoad(true)
    // The maximum upload file size allowed. (25mb)
    ->maxUploadFileSize('5M')
    // Prevent generated URLs from including "index.php"
    ->omitScriptNameInUrls(true)
    // Preload Single entries as Twig variables
    ->preloadSingles()
    // Prevent user enumeration attacks
    ->preventUserEnumeration(true)
    // The amount of time content preview tokens can be used before expiring. (7 days)
    ->previewTokenDuration(604800)
    // Whether asset URLs should be revved so browsers don’t load cached versions when they’re modified.
    ->revAssetUrls(true)
     // Whether an X-Powered-By: Craft CMS header should be sent
    ->sendPoweredByHeader(false)
    // Whether Craft should set users’ usernames to their email addresses, rather than let them set their username separately.
    ->useEmailAsUsername(true)
    // The tags to be included in the <head> tag of the control panel.
    ->cpHeadTags([
        ['link', ['rel' => 'icon', 'href' => '/dist/favicons/favicon.ico']],
    ])
    // The base path to the resources folder.
    ->resourceBasePath('@webroot/cpresources')
    // The base URL to the resources folder.
    ->resourceBaseUrl('@web/cpresources')
    // Aliases for URLs and paths
    ->aliases([
        '@baseUrl' => '/',
        '@webroot' => App::env('CRAFT_WEB_ROOT') ?? dirname(__DIR__) . '/web', // assuming 'web' is the public root folder
        '@assetsBaseUrl' => $assetsBaseUrl,
        '@assetsBasePath' => '@webroot/' . App::env('ASSETS_BASE_PATH'),
        '@secureAssetBasePath' => '@webroot/' . App::env('SECURE_ASSETS_FOLDER', ''),
    ])
;
