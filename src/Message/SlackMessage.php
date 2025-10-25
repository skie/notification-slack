<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Message;

use Closure;

/**
 * Slack Message
 *
 * Legacy Slack message with attachments support.
 *
 * @link https://api.slack.com/legacy/outmoded-messaging
 */
class SlackMessage
{
    /**
     * Message level constants
     */
    public const LEVEL_INFO = 'info';
    public const LEVEL_SUCCESS = 'success';
    public const LEVEL_WARNING = 'warning';
    public const LEVEL_ERROR = 'error';

    /**
     * The message level
     *
     * @var string
     */
    protected string $level = self::LEVEL_INFO;

    /**
     * The username to send the message from
     *
     * @var string|null
     */
    protected ?string $username = null;

    /**
     * The user emoji icon for the message
     *
     * @var string|null
     */
    protected ?string $icon = null;

    /**
     * The user image icon for the message
     *
     * @var string|null
     */
    protected ?string $image = null;

    /**
     * The channel to send the message on
     *
     * @var string|null
     */
    protected ?string $channel = null;

    /**
     * The text content of the message
     *
     * @var string
     */
    protected string $content = '';

    /**
     * Indicates if channel names and usernames should be linked
     *
     * @var int
     */
    protected int $linkNames = 0;

    /**
     * Indicates if a preview of links should be inlined
     *
     * @var bool|null
     */
    protected ?bool $unfurlLinks = null;

    /**
     * Indicates if a preview of media links should be inlined
     *
     * @var bool|null
     */
    protected ?bool $unfurlMedia = null;

    /**
     * The message's attachments
     *
     * @var array<\Cake\SlackNotification\Message\SlackAttachment>
     */
    protected array $attachments = [];

    /**
     * Create a new Slack message instance
     *
     * @param string $content Message content
     * @return static
     */
    public static function create(string $content = ''): static
    {
        $message = new static(); // @phpstan-ignore-line
        if ($content !== '') {
            $message->content = $content;
        }

        return $message;
    }

    /**
     * Indicate that the notification gives information
     *
     * @return static
     */
    public function info(): static
    {
        $this->level = self::LEVEL_INFO;

        return $this;
    }

    /**
     * Indicate that the notification gives information about success
     *
     * @return static
     */
    public function success(): static
    {
        $this->level = self::LEVEL_SUCCESS;

        return $this;
    }

    /**
     * Indicate that the notification gives information about a warning
     *
     * @return static
     */
    public function warning(): static
    {
        $this->level = self::LEVEL_WARNING;

        return $this;
    }

    /**
     * Indicate that the notification gives information about an error
     *
     * @return static
     */
    public function error(): static
    {
        $this->level = self::LEVEL_ERROR;

        return $this;
    }

    /**
     * Set a custom username and optional emoji icon
     *
     * @param string $username Username
     * @param string|null $icon Emoji icon (e.g., :ghost:)
     * @return static
     */
    public function from(string $username, ?string $icon = null): static
    {
        $this->username = $username;

        if ($icon !== null) {
            $this->icon = $icon;
        }

        return $this;
    }

    /**
     * Set a custom image icon
     *
     * @param string $image Image URL
     * @return static
     */
    public function image(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set the Slack channel
     *
     * @param string $channel Channel name (e.g., #general or @username)
     * @return static
     */
    public function to(string $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set the content of the message
     *
     * @param string $content Message content
     * @return static
     */
    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Define an attachment for the message
     *
     * @param \Closure $callback Callback to configure attachment
     * @return static
     */
    public function attachment(Closure $callback): static
    {
        $attachment = new SlackAttachment();
        $callback($attachment);
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Find and link channel names and usernames
     *
     * @return static
     */
    public function linkNames(): static
    {
        $this->linkNames = 1;

        return $this;
    }

    /**
     * Unfurl links to rich display
     *
     * @param bool $unfurlLinks Whether to unfurl links
     * @return static
     */
    public function unfurlLinks(bool $unfurlLinks): static
    {
        $this->unfurlLinks = $unfurlLinks;

        return $this;
    }

    /**
     * Unfurl media to rich display
     *
     * @param bool $unfurlMedia Whether to unfurl media
     * @return static
     */
    public function unfurlMedia(bool $unfurlMedia): static
    {
        $this->unfurlMedia = $unfurlMedia;

        return $this;
    }

    /**
     * Get the color for the message based on level
     *
     * @return string|null
     */
    public function getColor(): ?string
    {
        return match ($this->level) {
            self::LEVEL_SUCCESS => 'good',
            self::LEVEL_ERROR => 'danger',
            self::LEVEL_WARNING => 'warning',
            default => null,
        };
    }

    /**
     * Get the message content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get the channel
     *
     * @return string|null
     */
    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * Get the username
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Get the icon
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Get the image
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Get link names setting
     *
     * @return int
     */
    public function getLinkNames(): int
    {
        return $this->linkNames;
    }

    /**
     * Get unfurl links setting
     *
     * @return bool|null
     */
    public function getUnfurlLinks(): ?bool
    {
        return $this->unfurlLinks;
    }

    /**
     * Get unfurl media setting
     *
     * @return bool|null
     */
    public function getUnfurlMedia(): ?bool
    {
        return $this->unfurlMedia;
    }

    /**
     * Get attachments
     *
     * @return array<\Cake\SlackNotification\Message\SlackAttachment>
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }
}
