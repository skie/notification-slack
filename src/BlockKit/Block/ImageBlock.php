<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Block;

use Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject;
use InvalidArgumentException;
use LogicException;

/**
 * Image Block
 *
 * A block that displays an image.
 */
class ImageBlock implements BlockInterface
{
    /**
     * Block ID
     *
     * @var string|null
     */
    protected ?string $blockId = null;

    /**
     * Image URL
     *
     * @var string
     */
    protected string $url;

    /**
     * Alt text
     *
     * @var string|null
     */
    protected ?string $altText = null;

    /**
     * Title
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject|null
     */
    protected ?PlainTextOnlyTextObject $title = null;

    /**
     * Constructor
     *
     * @param string $url Image URL (max 3000 chars)
     * @param string|null $altText Alt text (max 2000 chars)
     */
    public function __construct(string $url, ?string $altText = null)
    {
        if (strlen($url) > 3000) {
            throw new InvalidArgumentException('Maximum length for the url field is 3000 characters.');
        }

        $this->url = $url;
        $this->altText = $altText;
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
     * Set the alt text
     *
     * @param string $altText Alt text (max 2000 chars)
     * @return static
     */
    public function alt(string $altText): static
    {
        if (strlen($altText) > 2000) {
            throw new InvalidArgumentException('Maximum length for the alt text field is 2000 characters.');
        }

        $this->altText = $altText;

        return $this;
    }

    /**
     * Set the title
     *
     * @param string $title Title text (max 2000 chars)
     * @return \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject
     */
    public function title(string $title): PlainTextOnlyTextObject
    {
        $this->title = $object = new PlainTextOnlyTextObject($title, 2000);

        return $object;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        if ($this->blockId !== null && strlen($this->blockId) > 255) {
            throw new InvalidArgumentException('Maximum length for the block_id field is 255 characters.');
        }

        if ($this->altText === null) {
            throw new LogicException('Alt text is required for an image block.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
            'title' => $this->title?->toArray(),
        ], fn($value) => $value !== null);

        return array_merge([
            'type' => 'image',
            'image_url' => $this->url,
            'alt_text' => $this->altText,
        ], $optionalFields);
    }
}
