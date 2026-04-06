<?php

namespace simple\simplemodule\twigextensions;

use Craft;
use Twig\Extension\AbstractExtension;
use simple\simplemodule\variables\HeadingVariable;
use simple\simplemodule\twigextensions\HeadingTagObject;

class HeadingExtension extends AbstractExtension
{
    private static ?HeadingVariable $headingVar = null;
    private static ?HeadingTagObject $headingTagObj = null;
    private static ?HeadingTagObject $headingTag1Obj = null;

    public function getName(): string
    {
        return 'heading-extension';
    }

    public function getGlobals(): array
    {
        if (self::$headingVar === null) {
            self::$headingVar = new HeadingVariable();
        }

        if (self::$headingTagObj === null) {
            self::$headingTagObj = new HeadingTagObject(self::$headingVar, false);
        }

        if (self::$headingTag1Obj === null) {
            self::$headingTag1Obj = new HeadingTagObject(self::$headingVar, true);
        }

        return [
            'headingTag' => self::$headingTagObj,
            'headingTag1' => self::$headingTag1Obj,
        ];
    }
}
