<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Element;

use LogicException;

/**
 * Image Element
 *
 * An image element for use in context blocks.
 */
class ImageElement implements ElementInterface, AccessoryInterface
{
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
    protected ?string $altText;

    /**
     * Constructor
     *
     * @param string $url Image URL
     * @param string|null $altText Alt text
     */
    public function __construct(string $url, ?string $altText = null)
    {
        $this->url = $url;
        $this->altText = $altText;
    }

    /**
     * Set the alt text
     *
     * @param string $altText Alt text
     * @return static
     */
    public function alt(string $altText): static
    {
        $this->altText = $altText;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        if ($this->altText === null) {
            throw new LogicException('Alt text is required for an image element.');
        }

        return [
            'type' => 'image',
            'image_url' => $this->url,
            'alt_text' => $this->altText,
        ];
    }
}
