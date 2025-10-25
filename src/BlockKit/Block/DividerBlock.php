<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Block;

use InvalidArgumentException;

/**
 * Divider Block
 *
 * A simple horizontal divider line.
 */
class DividerBlock implements BlockInterface
{
    /**
     * Block ID
     *
     * @var string|null
     */
    protected ?string $blockId = null;

    /**
     * Set the block identifier
     *
     * @param string $id Block ID (max 255 chars)
     * @return static
     */
    public function id(string $id): static
    {
        $this->blockId = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        if ($this->blockId !== null && strlen($this->blockId) > 255) {
            throw new InvalidArgumentException('Maximum length for the block_id field is 255 characters.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ], fn($value) => $value !== null);

        return array_merge([
            'type' => 'divider',
        ], $optionalFields);
    }
}
