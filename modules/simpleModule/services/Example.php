<?php

namespace simple\simplemodule\services;

use Craft;
use yii\base\Component;

/**
 * Example service
 */
class Example extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Example service
     *
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';

        return $result;
    }
}
