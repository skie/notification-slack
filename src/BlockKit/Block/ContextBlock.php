<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Block;

use Cake\SlackNotification\BlockKit\Composite\TextObject;
use Cake\SlackNotification\BlockKit\Element\ImageElement;
use InvalidArgumentException;
use LogicException;

/**
 * Context Block
 *
 * A block that displays contextual information (text and images).
 */
class ContextBlock implements BlockInterface
{
    /**
     * Block ID
     *
     * @var string|null
     */
    protected ?string $blockId = null;

    /**
     * Context elements
     *
     * @var array<\Cake\SlackNotification\BlockKit\Composite\TextObject|\Cake\SlackNotification\BlockKit\Element\ImageElement>
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
     * Add an image element
     *
     * @param string $imageUrl Image URL
     * @param string|null $altText Alt text
     * @return \Cake\SlackNotification\BlockKit\Element\ImageElement
     */
    public function image(string $imageUrl, ?string $altText = null): ImageElement
    {
        $element = new ImageElement($imageUrl, $altText);
        $this->elements[] = $element;

        return $element;
    }

    /**
     * Add a text element
     *
     * @param string $text Text content
     * @return \Cake\SlackNotification\BlockKit\Composite\TextObject
     */
    public function text(string $text): TextObject
    {
        $element = new TextObject($text);
        $this->elements[] = $element;

        return $element;
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
            throw new LogicException('There must be at least one element in each context block.');
        }

        if (count($this->elements) > 10) {
            throw new LogicException('There is a maximum of 10 elements in each context block.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ], fn($value) => $value !== null);

        return array_merge([
            'type' => 'context',
            'elements' => array_map(
                fn(TextObject|ImageElement $element) => $element->toArray(),
                $this->elements,
            ),
        ], $optionalFields);
    }
}
