<?php

namespace simple\simplemodule\services;

use Craft;
use craft\base\Component;
use craft\elements\Entry;
use craft\elements\Category;

class News extends Component
{
    public function filterNews()
    {
        // Initialize an array for relation filters
        $relatedTo = [];

        // Get query parameters from the URL (?keywords=...&category=...)
        $queryParams = Craft::$app->request->getQueryParams();
        $keywords = $queryParams['keywords'] ?? null;
        $category = $queryParams['category'] ?? null;

        // Get all categories from the 'news' category group
        $categories = Category::find()
            ->group('news')
            ->all();

        // If a category slug was provided in the query
        if ($category) {
            // Find the matching category element (as a query)
            $categoryEl = Category::find()->group('news')->slug($category);

            // If found, add it to the relatedTo condition
            if ($categoryEl) {
                $relatedTo[] = ['targetElement' => $categoryEl];
            }
        }

        // If more than one relatedTo condition is added, use 'and' operator
        if (sizeof($relatedTo) > 1) {
            array_unshift($relatedTo, 'and');
        }

        // Prepare the filter params to return back to the template (used for form value binding)
        $params = [
            'keywords' => $keywords,
            'category' => $categoryEl->slug ?? null, // Use slug if category was found
        ];

        // Create a query for news entries
        $results = Entry::find()
            ->section('news')                     // Only from the 'news' section
            ->relatedTo($relatedTo)               // Apply category filtering if set
            ->with(Craft::$app->config->custom->eager->news) // Eager-load configured fields
            ->search($keywords);                  // Apply keyword search if provided

        // If keywords were searched, order by relevance score
        if ($keywords) {
            $results->orderBy('score');
        } else {
            // Otherwise, order by most recent post date
            $results->orderBy('postDate DESC');
        }

        // Return the query, params, and available categories to the template
        return [
            'results' => $results,
            'params' => $params,
            'categories' => $categories,
        ];
    }
}
