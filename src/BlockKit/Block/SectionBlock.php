<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Block;

use Cake\SlackNotification\BlockKit\Composite\TextObject;
use Cake\SlackNotification\BlockKit\Element\AccessoryInterface;
use InvalidArgumentException;
use LogicException;

/**
 * Section Block
 *
 * A block that displays text with optional fields and accessory.
 */
class SectionBlock implements BlockInterface
{
    /**
     * Block ID
     *
     * @var string|null
     */
    protected ?string $blockId = null;

    /**
     * Main text
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\TextObject|null
     */
    protected ?TextObject $text = null;

    /**
     * Fields
     *
     * @var array<\Cake\SlackNotification\BlockKit\Composite\TextObject>
     */
    protected array $fields = [];

    /**
     * Accessory element
     *
     * @var \Cake\SlackNotification\BlockKit\Element\AccessoryInterface|null
     */
    protected ?AccessoryInterface $accessory = null;

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
     * Set the main text
     *
     * @param string $text Text content (max 3000 chars)
     * @return \Cake\SlackNotification\BlockKit\Composite\TextObject
     */
    public function text(string $text): TextObject
    {
        $this->text = $object = new TextObject($text, 3000);

        return $object;
    }

    /**
     * Add a field
     *
     * @param string $text Field text (max 2000 chars)
     * @return \Cake\SlackNotification\BlockKit\Composite\TextObject
     */
    public function field(string $text): TextObject
    {
        $field = new TextObject($text, 2000, 1);
        $this->fields[] = $field;

        return $field;
    }

    /**
     * Set the accessory element
     *
     * @param \Cake\SlackNotification\BlockKit\Element\AccessoryInterface $element Accessory element
     * @return static
     */
    public function accessory(AccessoryInterface $element): static
    {
        $this->accessory = $element;

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

        if ($this->text === null && empty($this->fields)) {
            throw new LogicException('A section requires at least the text or fields to be set.');
        }

        if (count($this->fields) > 10) {
            throw new LogicException('There is a maximum of 10 fields in each section block.');
        }

        $optionalFields = array_filter([
            'text' => $this->text?->toArray(),
            'block_id' => $this->blockId,
            'accessory' => $this->accessory?->toArray(),
            'fields' => !empty($this->fields)
                ? array_map(fn(TextObject $element) => $element->toArray(), $this->fields)
                : null,
        ], fn($value) => $value !== null);

        return array_merge([
            'type' => 'section',
        ], $optionalFields);
    }
}
