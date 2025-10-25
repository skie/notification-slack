<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Composite;

/**
 * Object Interface
 *
 * Interface for Slack Block Kit composition objects.
 */
interface ObjectInterface
{
    /**
     * Convert the object to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
