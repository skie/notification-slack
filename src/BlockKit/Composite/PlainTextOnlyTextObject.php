<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Composite;

use InvalidArgumentException;

/**
 * Plain Text Only Text Object
 *
 * A text object that only supports plain text.
 */
class PlainTextOnlyTextObject implements ObjectInterface
{
    /**
     * The text content
     *
     * @var string
     */
    protected string $text;

    /**
     * Maximum length for the text
     *
     * @var int
     */
    protected int $maxLength;

    /**
     * Minimum length for the text
     *
     * @var int
     */
    protected int $minLength;

    /**
     * Indicates whether emojis should be escaped
     *
     * @var bool|null
     */
    protected ?bool $emoji = null;

    /**
     * Constructor
     *
     * @param string $text Text content
     * @param int $maxLength Maximum length
     * @param int $minLength Minimum length
     * @throws \InvalidArgumentException
     */
    public function __construct(string $text, int $maxLength = 3000, int $minLength = 1)
    {
        $textLength = mb_strlen($text);

        if ($textLength < $minLength) {
            throw new InvalidArgumentException(
                sprintf('Minimum length for the text field is %d characters.', $minLength),
            );
        }

        if ($textLength > $maxLength) {
            throw new InvalidArgumentException(
                sprintf('Maximum length for the text field is %d characters.', $maxLength),
            );
        }

        $this->text = $text;
        $this->maxLength = $maxLength;
        $this->minLength = $minLength;
    }

    /**
     * Set emoji rendering
     *
     * @param bool $emoji Whether to render emojis
     * @return static
     */
    public function emoji(bool $emoji = true): static
    {
        $this->emoji = $emoji;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $optionalFields = array_filter([
            'emoji' => $this->emoji,
        ], fn($value) => $value !== null);

        return array_merge([
            'type' => 'plain_text',
            'text' => $this->text,
        ], $optionalFields);
    }
}
