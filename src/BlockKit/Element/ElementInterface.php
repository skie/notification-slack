<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Element;

/**
 * Element Interface
 *
 * Interface for Slack Block Kit elements.
 */
interface ElementInterface
{
    /**
     * Convert the element to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
