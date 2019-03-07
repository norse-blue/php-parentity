<?php

namespace NorseBlue\Parentity\Traits;

/**
 * Trait ResolvesProperties
 *
 * @package NorseBlue\Parentity\Traits
 */
trait ResolvesProperties
{
    /**
     * Resolves a value from a property, a map or the default value.
     *
     * @param string $property
     * @param mixed $default
     * @param array $resolveMap
     *
     * @return mixed|null
     */
    private function resolveValue(string $property, $default = null, array $resolveMap = [])
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        if ($default !== null) {
            return $default;
        }

        return $resolveMap[$property] ?? $default;
    }
}
