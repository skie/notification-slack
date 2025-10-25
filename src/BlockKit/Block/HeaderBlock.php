<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Block;

use Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject;
use Closure;
use InvalidArgumentException;

/**
 * Header Block
 *
 * A header block with large, bold text.
 */
class HeaderBlock implements BlockInterface
{
    /**
     * Block ID
     *
     * @var string|null
     */
    protected ?string $blockId = null;

    /**
     * Header text
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject
     */
    protected PlainTextOnlyTextObject $text;

    /**
     * Constructor
     *
     * @param string $text Header text (max 150 chars)
     * @param \Closure|null $callback Optional callback to configure text object
     */
    public function __construct(string $text, ?Closure $callback = null)
    {
        $this->text = $object = new PlainTextOnlyTextObject($text, 150);

        if ($callback !== null) {
            $callback($object);
        }
    }

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
            'type' => 'header',
            'text' => $this->text->toArray(),
        ], $optionalFields);
    }
}
