<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Block;

use Cake\SlackNotification\BlockKit\Element\ButtonElement;
use Cake\SlackNotification\BlockKit\Element\ElementInterface;
use InvalidArgumentException;
use LogicException;

/**
 * Actions Block
 *
 * A block that contains interactive elements (buttons, selects, etc.).
 */
class ActionsBlock implements BlockInterface
{
    /**
     * Block ID
     *
     * @var string|null
     */
    protected ?string $blockId = null;

    /**
     * Interactive elements
     *
     * @var array<\Cake\SlackNotification\BlockKit\Element\ElementInterface>
     */
    protected array $elements = [];

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
     * Add a button element
     *
     * @param string $text Button text
     * @return \Cake\SlackNotification\BlockKit\Element\ButtonElement
     */
    public function button(string $text): ButtonElement
    {
        $button = new ButtonElement($text);
        $this->elements[] = $button;

        return $button;
    }

    /**
     * Add a custom element
     *
     * @param \Cake\SlackNotification\BlockKit\Element\ElementInterface $element Element
     * @return static
     */
    public function element(ElementInterface $element): static
    {
        $this->elements[] = $element;

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

        if (empty($this->elements)) {
            throw new LogicException('There must be at least one element in each actions block.');
        }

        if (count($this->elements) > 25) {
            throw new LogicException('There is a maximum of 25 elements in each actions block.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ], fn($value) => $value !== null);

        return array_merge([
            'type' => 'actions',
            'elements' => array_map(fn(ElementInterface $element) => $element->toArray(), $this->elements),
        ], $optionalFields);
    }
}
