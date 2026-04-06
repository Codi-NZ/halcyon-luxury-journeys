<?php

namespace simple\simplemodule\twigextensions;

use simple\simplemodule\variables\HeadingVariable;

class HeadingTagObject
{
    private HeadingVariable $headingVar;
    private ?int $forcedLevel;
    private ?string $currentTag = null;
    private int $callCount = 0;

    /**
     * @param HeadingVariable $headingVar
     * @param int|null $forcedLevel If null, auto-increments. If set (1-6), forces that level
     */
    public function __construct(HeadingVariable $headingVar, ?int $forcedLevel = null)
    {
        $this->headingVar = $headingVar;
        $this->forcedLevel = $forcedLevel;
    }

    public function __toString(): string
    {
        // Odd calls (1st, 3rd, 5th) = opening tags - get new heading
        // Even calls (2nd, 4th, 6th) = closing tags - return cached
        $isOpeningTag = ($this->callCount % 2 == 0);
        
        if ($isOpeningTag) {
            // Get the tag (this updates the shared state)
            if ($this->forcedLevel !== null) {
                // Force specific level
                $this->currentTag = $this->headingVar->next($this->forcedLevel);
            } else {
                // Auto-increment (continues from last used level)
                $this->currentTag = $this->headingVar->next();
            }
        }
        // Closing tag: return cached value (no state change)
        
        $this->callCount++;
        return $this->currentTag ?? 'h1';
    }
}
