<?php

namespace simple\simplemodule\variables;

/**
 * Heading Variable
 * 
 * Provides a way to store and retrieve heading state variables across template rendering
 * for SEO-friendly heading progression in Twig templates.
 */
class HeadingVariable
{
    /**
     * @var array Storage for context variables
     */
    private static array $storage = [];

    /**
     * Get a variable from context
     * 
     * @param string $key The variable key
     * @param mixed $default The default value if key doesn't exist
     * @return mixed
     */
    public function getVariable(string $key, $default = null)
    {
        return self::$storage[$key] ?? $default;
    }

    /**
     * Set a variable in context
     * 
     * @param string $key The variable key
     * @param mixed $value The value to set
     * @return void
     */
    public function setVariable(string $key, $value): void
    {
        self::$storage[$key] = $value;
    }

    /**
     * Check if a variable exists in context
     * 
     * @param string $key The variable key
     * @return bool
     */
    public function hasVariable(string $key): bool
    {
        return isset(self::$storage[$key]);
    }

    /**
     * Remove a variable from context
     * 
     * @param string $key The variable key
     * @return void
     */
    public function removeVariable(string $key): void
    {
        unset(self::$storage[$key]);
    }

    /**
     * Clear all context variables
     *
     * @return void
     */
    public function clear(): void
    {
        self::$storage = [];
    }

    /**
     * Get the next heading tag in sequence
     * 
     * @param int|null $preferred Preferred heading level (1-6), or null for auto-increment
     * @return string The heading tag name (e.g., "h1", "h2")
     */
    public function next(?int $preferred = null): string
    {
        $currentLevel = $this->getVariable('seoHeadingLast', 0);
        $currentH1 = $this->getVariable('seoHeadingH1', false);
        $isForced = $preferred !== null;
        
        // Determine requested level
        $requested = $preferred ?? ($currentLevel + 1);
        
        // Clamp to valid range
        $requested = max(1, min(6, $requested));
        
        // Enforce single H1
        if ($requested == 1 && $currentH1) {
            $requested = $currentLevel >= 1 ? $currentLevel + 1 : 2;
        }
        
        // Prevent skipping levels or going backwards
        if ($isForced) {
            // For forced levels: allow skipping, but don't go backwards
            if ($requested <= $currentLevel) {
                $actual = $currentLevel; // Don't go backwards
            } else {
                $actual = $requested; // Allow forced levels to skip (H1 -> H3 is OK)
            }
        } else {
            // Auto-increment: prevent skipping levels
            if ($currentLevel == 0) {
                $actual = $requested;
            } elseif ($requested <= $currentLevel) {
                $actual = $currentLevel;
            } elseif ($requested > $currentLevel + 1) {
                $actual = $currentLevel + 1;
            } else {
                $actual = $requested;
            }
        }
        
        // Final clamp
        $actual = max(1, min(6, $actual));
        
        // Update state
        $this->setVariable('seoHeadingLast', $actual);
        if ($actual == 1) {
            $this->setVariable('seoHeadingH1', true);
        }
        
        return 'h' . $actual;
    }
}
