<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Block;

/**
 * Block Interface
 *
 * Interface for Slack Block Kit blocks.
 */
interface BlockInterface
{
    /**
     * Convert the block to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
