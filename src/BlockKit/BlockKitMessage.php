<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit;

use Cake\SlackNotification\BlockKit\Block\ActionsBlock;
use Cake\SlackNotification\BlockKit\Block\BlockInterface;
use Cake\SlackNotification\BlockKit\Block\ContextBlock;
use Cake\SlackNotification\BlockKit\Block\DividerBlock;
use Cake\SlackNotification\BlockKit\Block\HeaderBlock;
use Cake\SlackNotification\BlockKit\Block\ImageBlock;
use Cake\SlackNotification\BlockKit\Block\SectionBlock;
use Closure;
use LogicException;

/**
 * BlockKit Message
 *
 * Modern Slack message using Block Kit.
 */
class BlockKitMessage
{
    /**
     * Channel to send to
     *
     * @var string|null
     */
    protected ?string $channel = null;

    /**
     * Fallback text
     *
     * @var string|null
     */
    protected ?string $text = null;

    /**
     * Message blocks
     *
     * @var array<\Cake\SlackNotification\BlockKit\Block\BlockInterface|array<string, mixed>>
     */
    protected array $blocks = [];

    /**
     * Emoji icon
     *
     * @var string|null
     */
    protected ?string $icon = null;

    /**
     * Image icon URL
     *
     * @var string|null
     */
    protected ?string $image = null;

    /**
     * Event metadata
     *
     * @var \Cake\SlackNotification\BlockKit\EventMetadata|null
     */
    protected ?EventMetadata $metaData = null;

    /**
     * Markdown parsing enabled
     *
     * @var bool|null
     */
    protected ?bool $mrkdwn = null;

    /**
     * Unfurl links
     *
     * @var bool|null
     */
    protected ?bool $unfurlLinks = null;

    /**
     * Unfurl media
     *
     * @var bool|null
     */
    protected ?bool $unfurlMedia = null;

    /**
     * Username
     *
     * @var string|null
     */
    protected ?string $username = null;

    /**
     * Thread timestamp
     *
     * @var string|null
     */
    protected ?string $threadTs = null;

    /**
     * Broadcast reply to thread
     *
     * @var bool|null
     */
    protected ?bool $broadcastReply = null;

    /**
     * Set the channel
     *
     * @param string $channel Channel name
     * @return static
     */
    public function to(string $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set the fallback text
     *
     * @param string $text Fallback text
     * @return static
     */
    public function text(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Add an actions block
     *
     * @param \Closure $callback Callback to configure block
     * @return static
     */
    public function actionsBlock(Closure $callback): static
    {
        $block = new ActionsBlock();
        $callback($block);
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Add a context block
     *
     * @param \Closure $callback Callback to configure block
     * @return static
     */
    public function contextBlock(Closure $callback): static
    {
        $block = new ContextBlock();
        $callback($block);
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Add a divider block
     *
     * @return static
     */
    public function dividerBlock(): static
    {
        $this->blocks[] = new DividerBlock();

        return $this;
    }

    /**
     * Add a header block
     *
     * @param string $text Header text
     * @param \Closure|null $callback Optional callback to configure block
     * @return static
     */
    public function headerBlock(string $text, ?Closure $callback = null): static
    {
        $this->blocks[] = new HeaderBlock($text, $callback);

        return $this;
    }

    /**
     * Add an image block
     *
     * @param string $url Image URL
     * @param \Closure|string|null $altText Alt text or callback
     * @param \Closure|null $callback Optional callback to configure block
     * @return static
     */
    public function imageBlock(string $url, Closure|string|null $altText = null, ?Closure $callback = null): static
    {
        if ($altText instanceof Closure) {
            $callback = $altText;
            $altText = null;
        }

        $image = new ImageBlock($url, $altText);

        if ($callback !== null) {
            $callback($image);
        }

        $this->blocks[] = $image;

        return $this;
    }

    /**
     * Add a section block
     *
     * @param \Closure $callback Callback to configure block
     * @return static
     */
    public function sectionBlock(Closure $callback): static
    {
        $block = new SectionBlock();
        $callback($block);
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Set emoji icon
     *
     * @param string $emoji Emoji (e.g., :ghost:)
     * @return static
     */
    public function emoji(string $emoji): static
    {
        $this->image = null;
        $this->icon = $emoji;

        return $this;
    }

    /**
     * Set image icon
     *
     * @param string $image Image URL
     * @return static
     */
    public function image(string $image): static
    {
        $this->icon = null;
        $this->image = $image;

        return $this;
    }

    /**
     * Set metadata
     *
     * @param string $eventType Event type
     * @param array<string, mixed> $payload Event payload
     * @return static
     */
    public function metadata(string $eventType, array $payload = []): static
    {
        $this->metaData = new EventMetadata($eventType, $payload);

        return $this;
    }

    /**
     * Disable markdown parsing
     *
     * @return static
     */
    public function disableMarkdownParsing(): static
    {
        $this->mrkdwn = false;

        return $this;
    }

    /**
     * Unfurl links
     *
     * @param bool $unfurlLinks Whether to unfurl links
     * @return static
     */
    public function unfurlLinks(bool $unfurlLinks = true): static
    {
        $this->unfurlLinks = $unfurlLinks;

        return $this;
    }

    /**
     * Unfurl media
     *
     * @param bool $unfurlMedia Whether to unfurl media
     * @return static
     */
    public function unfurlMedia(bool $unfurlMedia = true): static
    {
        $this->unfurlMedia = $unfurlMedia;

        return $this;
    }

    /**
     * Set username
     *
     * @param string $username Username
     * @return static
     */
    public function username(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set thread timestamp
     *
     * @param string|null $threadTimestamp Thread timestamp
     * @return static
     */
    public function threadTimestamp(?string $threadTimestamp): static
    {
        $this->threadTs = $threadTimestamp;

        return $this;
    }

    /**
     * Broadcast reply to thread
     *
     * @param bool|null $broadcastReply Whether to broadcast
     * @return static
     */
    public function broadcastReply(?bool $broadcastReply = true): static
    {
        $this->broadcastReply = $broadcastReply;

        return $this;
    }

    /**
     * Use Block Kit template JSON
     *
     * @param string $template JSON template
     * @return static
     * @throws \JsonException
     * @throws \LogicException
     */
    public function usingBlockKitTemplate(string $template): static
    {
        $blocks = json_decode($template, true, 512, JSON_THROW_ON_ERROR);

        if (!array_key_exists('blocks', $blocks)) {
            throw new LogicException('The blocks array key is missing.');
        }

        array_push($this->blocks, ...$blocks['blocks']);

        return $this;
    }

    /**
     * Convert to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        if (empty($this->blocks) && $this->text === null) {
            throw new LogicException('Slack messages must contain at least a text message or block.');
        }

        if (count($this->blocks) > 50) {
            throw new LogicException('Slack messages can only contain up to 50 blocks.');
        }

        $optionalFields = array_filter([
            'text' => $this->text,
            'blocks' => !empty($this->blocks)
                ? array_map(
                    fn($block) => $block instanceof BlockInterface ? $block->toArray() : $block,
                    $this->blocks,
                )
                : null,
            'icon_emoji' => $this->icon,
            'icon_url' => $this->image,
            'metadata' => $this->metaData?->toArray(),
            'mrkdwn' => $this->mrkdwn,
            'thread_ts' => $this->threadTs,
            'reply_broadcast' => $this->broadcastReply,
            'unfurl_links' => $this->unfurlLinks,
            'unfurl_media' => $this->unfurlMedia,
            'username' => $this->username,
        ], fn($value) => $value !== null);

        return array_merge([
            'channel' => $this->channel,
        ], $optionalFields);
    }
}
