<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Message;

/**
 * Slack Attachment Field
 *
 * Represents a field in a Slack attachment.
 */
class SlackAttachmentField
{
    /**
     * Constructor
     *
     * @param string $title Field title
     * @param string $value Field value
     * @param bool $short Whether field is short
     */
    public function __construct(
        protected string $title = '',
        protected string $value = '',
        protected bool $short = true,
    ) {
    }

    /**
     * Set the title
     *
     * @param string $title Field title
     * @return static
     */
    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the value
     *
     * @param string $value Field value
     * @return static
     */
    public function value(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Set whether field is short
     *
     * @param bool $short Whether field is short
     * @return static
     */
    public function short(bool $short = true): static
    {
        $this->short = $short;

        return $this;
    }

    /**
     * Convert to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'value' => $this->value,
            'short' => $this->short,
        ];
    }
}
