<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Element\Trait;

/**
 * Generates Default IDs Trait
 *
 * Provides default ID generation for interactive elements.
 *
 * @phpstan-type SlugString string
 */
trait GeneratesDefaultIdsTrait
{
    /**
     * Resolve a default ID
     *
     * @param string $prefix ID prefix
     * @param string $text Text to generate ID from
     * @return string
     */
    protected function resolveDefaultId(string $prefix, string $text): string
    {
        $replaced = preg_replace('/[^A-Za-z0-9-]+/', '_', $text);
        $slug = strtolower(trim((string)$replaced, '_'));

        return $prefix . substr($slug, 0, 50);
    }
}
