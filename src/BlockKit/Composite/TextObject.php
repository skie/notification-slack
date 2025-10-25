<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Composite;

/**
 * Text Object
 *
 * A text object that supports both plain text and markdown.
 */
class TextObject extends PlainTextOnlyTextObject
{
    /**
     * The text type
     *
     * @var string
     */
    protected string $type = 'plain_text';

    /**
     * Whether to skip preprocessing for markdown
     *
     * @var bool|null
     */
    protected ?bool $verbatim = null;

    /**
     * Use markdown formatting
     *
     * @return static
     */
    public function markdown(): static
    {
        $this->type = 'mrkdwn';

        return $this;
    }

    /**
     * Skip auto-linking of URLs and mentions
     *
     * @return static
     */
    public function verbatim(): static
    {
        $this->verbatim = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $base = parent::toArray();

        $base['type'] = $this->type;

        if ($this->type === 'mrkdwn') {
            unset($base['emoji']);

            if ($this->verbatim !== null) {
                $base['verbatim'] = $this->verbatim;
            }
        } else {
            unset($base['verbatim']);
        }

        return $base;
    }
}
