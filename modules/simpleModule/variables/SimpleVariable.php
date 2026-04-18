<?php

namespace simple\simplemodule\variables;

use Craft;
use craft\elements\Entry;
use simple\simplemodule\Module as SimpleModule;

class SimpleVariable
{
    /**
     * Globals
     */
    public function globals()
    {
        $currentSite = Craft::$app->getSites()->currentSite->handle;

        return Craft::$app->cache->getOrSet('CACHE_GLOBALS' . $currentSite, function ($cache) {
            /**
             * Globals Single entry (replaces legacy Global Sets)
             */
            $gs = Entry::find()
                ->section('globals')
                ->with([
                    'fallbackBanner',
                    'fallbackGeneric',
                ])
                ->one();

            /**
             * Business Details
             */
            $businessDetails = [
                'fullBusinessName' => $gs->fullBusinessName ?? null,
                'tagline' => $gs->tagline ?? null,
                'businessDescription' => $gs->businessDescription ?? null,
                'contact' => [
                    'email' => $gs->emailAddress ?? null,
                    'phone' => $gs->phoneNumber ?? null,
                    'fax' => $gs->faxNumber ?? null,
                    'addressLineOne' => $gs->addressLineOne ?? null,
                    'addressLineTwo' => $gs->addressLineTwo ?? null,
                    'suburb' => $gs->suburb ?? null,
                    'postcode' => $gs->postcode ?? null,
                    'state' => $gs->state ?? null,
                ],
                'social' => [
                    'facebook' => $gs->facebookUsername ?? null,
                    'instagram' => $gs->instagramUsername ?? null,
                    'linkedin' => $gs->linkedinPage ?? null,
                    'x' => $gs->xHandle ?? null,
                    'youtube' => $gs->youtubeChannel ?? null,
                ],
            ];

            /**
             * Fallbacks
             */
            $fallbacks = [
                'images' => [
                    'banner' => $gs->fallbackBanner[0] ?? null,
                    'generic' => $gs->fallbackGeneric[0] ?? null,
                ],
            ];

            /**
             * Misc
             */
            $misc = [
                'acknowledgement' => $gs->acknowledgement ?? null,
            ];

            /**
             * Listings
             */
            $allListings = Entry::find()
                ->section('sitemap')
                ->type([
                    'newsListing',
                    'peopleListing',
                ])
                ->with('image')
                ->all();

            $listings = [
                'all' => array_merge(
                    ...array_map(fn ($e) => [str_replace('Listing', '', $e->type->handle) => $e], $allListings)
                ),
            ];

            $listingByEntryType = [
                'article' => $listings['all']['news'] ?? [],
                'person' => $listings['all']['people'] ?? [],
            ];


            return [
                'businessDetails' => $businessDetails,
                'fallbacks' => $fallbacks,
                'misc' => $misc,
                'listings' => $listings,
                'listingByEntryType' => $listingByEntryType,
            ];
        }, 60*60*24);
    }

    /**
     * News
     */
    public function filterNews()
    {
        return SimpleModule::$plugin->news->filterNews();
    }

    public function calendarMonthUrls($month, $year)
    {
        return SimpleModule::$plugin->events->calendarMonthUrls($month, $year);
    }

    // This function is a helper to prepre the data used to generate the calendar view
    // This is a boost for the performance.
    public function prepareDataForEventsCalendarView($events, $anyDayInMonthYmd)
    {
        $eventsInfo = [];
        foreach ($events as $event) {
            $occurrences = $event->eventDates->occurrences;
            $hasOccurrencesByDate = [];
            $dateYm = date('Y-m-', strtotime($anyDayInMonthYmd));
            for ($i = 0; $i < date('t', strtotime($anyDayInMonthYmd)); $i++) {
                $targetDate = $dateYm . str_pad(($i + 1), 2, '0', STR_PAD_LEFT);
                $hasOccurrencesByDate[$targetDate] = false;
            }
            foreach ($occurrences as $occurrence) {
                $key = date('Y-m-d', strtotime($occurrence['from']));
                if (isset($hasOccurrencesByDate[$key])) {
                    $hasOccurrencesByDate[$key] = true;
                }
            }
            $eventsInfo[] = [
                'event' => $event,
                'hasOccurrencesByDate' => $hasOccurrencesByDate
            ];
        }
        return $eventsInfo;
    }
}
