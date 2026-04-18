<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;
use craft\elements\Entry;
use craft\elements\GlobalSet;
use craft\models\EntryType;
use craft\enums\PropagationMethod;
use craft\models\Section;
use craft\models\Section_SiteSettings;

/**
 * Migrates content from Global Sets (businessDetails, fallbacks, globalFooter, misc)
 * into a new "Globals" Single section entry.
 */
class m260412_000000_migrate_globals_to_single extends Migration
{
    public function safeUp(): bool
    {
        $entriesService = Craft::$app->getEntries();

        // ── 1. Ensure the entry type exists ──
        $entryType = $entriesService->getEntryTypeByHandle('globals');

        if (!$entryType) {
            echo "    > Entry type 'globals' not found — creating via project config…\n";
            $pc = Craft::$app->getProjectConfig();
            $uid = '73988ff7-7dd9-4e10-ab92-d16f04347dd7';
            $cfg = $pc->get("entryTypes.{$uid}");
            if ($cfg) {
                $pc->processConfigChanges("entryTypes.{$uid}");
                $entryType = $entriesService->getEntryTypeByHandle('globals');
            }
        }

        if (!$entryType) {
            echo "    > ERROR: Could not create or find the 'globals' entry type.\n";
            return false;
        }

        echo "    > Entry type 'globals' ready (ID {$entryType->id}).\n";

        // ── 2. Ensure the section exists ──
        $section = $entriesService->getSectionByHandle('globals');

        if (!$section) {
            echo "    > Section 'globals' not found — creating programmatically…\n";

            $section = new Section();
            $section->name = 'Globals';
            $section->handle = 'globals';
            $section->type = Section::TYPE_SINGLE;
            $section->enableVersioning = true;
            $section->maxAuthors = 1;
            $section->propagationMethod = PropagationMethod::All;
            $section->setEntryTypes([$entryType]);

            $primarySite = Craft::$app->getSites()->getPrimarySite();
            $siteSettings = new Section_SiteSettings();
            $siteSettings->siteId = $primarySite->id;
            $siteSettings->enabledByDefault = true;
            $siteSettings->hasUrls = false;

            $section->setSiteSettings([$primarySite->id => $siteSettings]);

            if (!$entriesService->saveSection($section)) {
                echo "    > ERROR: Failed to save section.\n";
                return false;
            }

            echo "    > Section created (ID {$section->id}).\n";
        } else {
            echo "    > Section 'globals' ready (ID {$section->id}).\n";
        }

        // ── 3. Get or create the Single's entry ──
        $globalsEntry = Entry::find()
            ->section('globals')
            ->status(null)
            ->one();

        if (!$globalsEntry) {
            echo "    > Creating Globals entry…\n";
            $globalsEntry = new Entry();
            $globalsEntry->sectionId = $section->id;
            $globalsEntry->typeId = $entryType->id;
            $globalsEntry->title = 'Globals';
            if (!Craft::$app->getElements()->saveElement($globalsEntry)) {
                echo "    > ERROR: Failed to create entry.\n";
                return false;
            }
        }

        echo "    > Globals entry ready (ID {$globalsEntry->id}).\n";

        // ── 4. Copy data from Global Sets ──

        // --- Business Details ---
        $businessDetails = GlobalSet::find()->handle('businessDetails')->one();
        if ($businessDetails) {
            echo "    > Migrating Business Details…\n";
            $globalsEntry->setFieldValue('fullBusinessName', $businessDetails->fullBusinessName ?? null);
            $globalsEntry->setFieldValue('tagline', $businessDetails->tagline ?? null);
            $globalsEntry->setFieldValue('businessDescription', $businessDetails->businessDescription ?? null);
            $globalsEntry->setFieldValue('emailAddress', $businessDetails->emailAddress ?? null);
            $globalsEntry->setFieldValue('phoneNumber', $businessDetails->phoneNumber ?? null);
            $globalsEntry->setFieldValue('faxNumber', $businessDetails->faxNumber ?? null);
            $globalsEntry->setFieldValue('addressLineOne', $businessDetails->addressLineOne ?? null);
            $globalsEntry->setFieldValue('addressLineTwo', $businessDetails->addressLineTwo ?? null);
            $globalsEntry->setFieldValue('suburb', $businessDetails->suburb ?? null);
            $globalsEntry->setFieldValue('postcode', $businessDetails->postcode ?? null);
            $globalsEntry->setFieldValue('state', $businessDetails->state ?? null);
            $globalsEntry->setFieldValue('facebookUsername', $businessDetails->facebookUsername ?? null);
            $globalsEntry->setFieldValue('instagramUsername', $businessDetails->instagramUsername ?? null);
            $globalsEntry->setFieldValue('linkedinPage', $businessDetails->linkedinPage ?? null);
            $globalsEntry->setFieldValue('xHandle', $businessDetails->xHandle ?? null);
            $globalsEntry->setFieldValue('youtubeChannel', $businessDetails->youtubeChannel ?? null);
        } else {
            echo "    > Business Details global set not found, skipping.\n";
        }

        // --- Global Footer ---
        $globalFooter = GlobalSet::find()->handle('globalFooter')->one();
        if ($globalFooter) {
            echo "    > Migrating Footer…\n";
            $globalsEntry->setFieldValue('legalLinks', $globalFooter->legalLinks ?? null);
        } else {
            echo "    > Footer global set not found, skipping.\n";
        }

        // --- Fallbacks ---
        $fallbacks = GlobalSet::find()->handle('fallbacks')->one();
        if ($fallbacks) {
            echo "    > Migrating Fallbacks…\n";

            $bannerIds = $fallbacks->getFieldValue('fallbackBanner')->ids();
            if (!empty($bannerIds)) {
                $globalsEntry->setFieldValue('fallbackBanner', $bannerIds);
            }

            $genericIds = $fallbacks->getFieldValue('fallbackGeneric')->ids();
            if (!empty($genericIds)) {
                $globalsEntry->setFieldValue('fallbackGeneric', $genericIds);
            }

            $globalsEntry->setFieldValue('video', $fallbacks->video ?? null);
        } else {
            echo "    > Fallbacks global set not found, skipping.\n";
        }

        // --- Misc ---
        $misc = GlobalSet::find()->handle('misc')->one();
        if ($misc) {
            echo "    > Migrating Misc…\n";
            $globalsEntry->setFieldValue('acknowledgement', $misc->acknowledgement ?? null);
        } else {
            echo "    > Misc global set not found, skipping.\n";
        }

        // ── 5. Save the entry ──
        $globalsEntry->setScenario(\craft\base\Element::SCENARIO_ESSENTIALS);
        if (!Craft::$app->getElements()->saveElement($globalsEntry)) {
            echo "    > ERROR: Failed to save Globals entry:\n";
            foreach ($globalsEntry->getErrors() as $field => $errors) {
                foreach ($errors as $error) {
                    echo "      - {$field}: {$error}\n";
                }
            }
            return false;
        }

        echo "    > Globals entry saved successfully.\n";

        // ── 6. Delete old Global Sets ──
        $handles = ['businessDetails', 'fallbacks', 'globalFooter', 'misc'];
        foreach ($handles as $handle) {
            $gs = GlobalSet::find()->handle($handle)->one();
            if ($gs) {
                echo "    > Deleting global set: {$handle}…\n";
                Craft::$app->getElements()->deleteElement($gs);
            }
        }

        echo "    > Migration complete.\n";
        return true;
    }

    public function safeDown(): bool
    {
        echo "    > This migration cannot be reverted.\n";
        return false;
    }
}
