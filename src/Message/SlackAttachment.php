<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Message;

use Closure;

/**
 * Slack Attachment
 *
 * Represents an attachment for a Slack message.
 */
class SlackAttachment
{
    /**
     * The attachment's title
     *
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * The attachment's URL
     *
     * @var string|null
     */
    protected ?string $url = null;

    /**
     * The attachment's pretext
     *
     * @var string|null
     */
    protected ?string $pretext = null;

    /**
     * The attachment's text content
     *
     * @var string|null
     */
    protected ?string $content = null;

    /**
     * A plain-text summary of the attachment
     *
     * @var string|null
     */
    protected ?string $fallback = null;

    /**
     * The attachment's color
     *
     * @var string|null
     */
    protected ?string $color = null;

    /**
     * The attachment's fields
     *
     * @var array<string|int, string|\Cake\SlackNotification\Message\SlackAttachmentField>
     */
    protected array $fields = [];

    /**
     * The fields containing markdown
     *
     * @var array<string>
     */
    protected array $markdown = [];

    /**
     * The attachment's image url
     *
     * @var string|null
     */
    protected ?string $imageUrl = null;

    /**
     * The attachment's thumb url
     *
     * @var string|null
     */
    protected ?string $thumbUrl = null;

    /**
     * The attachment's actions
     *
     * @var array<array<string, mixed>>
     */
    protected array $actions = [];

    /**
     * The attachment author's name
     *
     * @var string|null
     */
    protected ?string $authorName = null;

    /**
     * The attachment author's link
     *
     * @var string|null
     */
    protected ?string $authorLink = null;

    /**
     * The attachment author's icon
     *
     * @var string|null
     */
    protected ?string $authorIcon = null;

    /**
     * The attachment's footer
     *
     * @var string|null
     */
    protected ?string $footer = null;

    /**
     * The attachment's footer icon
     *
     * @var string|null
     */
    protected ?string $footerIcon = null;

    /**
     * The attachment's timestamp
     *
     * @var int|null
     */
    protected ?int $timestamp = null;

    /**
     * The attachment's callback ID
     *
     * @var string|null
     */
    protected ?string $callbackId = null;

    /**
     * Set the title of the attachment
     *
     * @param string $title Title text
     * @param string|null $url Optional URL
     * @return static
     */
    public function title(string $title, ?string $url = null): static
    {
        $this->title = $title;
        $this->url = $url;

        return $this;
    }

    /**
     * Set the pretext of the attachment
     *
     * @param string $pretext Pretext
     * @return static
     */
    public function pretext(string $pretext): static
    {
        $this->pretext = $pretext;

        return $this;
    }

    /**
     * Set the content (text) of the attachment
     *
     * @param string $content Content text
     * @return static
     */
    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * A plain-text summary of the attachment
     *
     * @param string $fallback Fallback text
     * @return static
     */
    public function fallback(string $fallback): static
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * Set the color of the attachment
     *
     * @param string $color Color (good, warning, danger, or hex code)
     * @return static
     */
    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Add a field to the attachment
     *
     * @param \Closure|string $title Field title or callback
     * @param string $content Field content
     * @param bool $short Whether field is short
     * @return static
     */
    public function field(Closure|string $title, string $content = '', bool $short = true): static
    {
        if ($title instanceof Closure) {
            $attachmentField = new SlackAttachmentField();
            $title($attachmentField);
            $this->fields[] = $attachmentField;

            return $this;
        }

        $this->fields[] = new SlackAttachmentField($title, $content, $short);

        return $this;
    }

    /**
     * Set the fields of the attachment
     *
     * @param array<string|int, string|\Cake\SlackNotification\Message\SlackAttachmentField> $fields Fields array
     * @return static
     */
    public function fields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Set the fields containing markdown
     *
     * @param array<string> $fields Field names
     * @return static
     */
    public function markdown(array $fields): static
    {
        $this->markdown = $fields;

        return $this;
    }

    /**
     * Set the image URL
     *
     * @param string $url Image URL
     * @return static
     */
    public function image(string $url): static
    {
        $this->imageUrl = $url;

        return $this;
    }

    /**
     * Set the URL to the attachment thumbnail
     *
     * @param string $url Thumbnail URL
     * @return static
     */
    public function thumb(string $url): static
    {
        $this->thumbUrl = $url;

        return $this;
    }

    /**
     * Add an action (button) under the attachment
     *
     * @param string $title Button text
     * @param string $url Button URL
     * @param string $style Button style (primary, danger)
     * @return static
     */
    public function action(string $title, string $url, string $style = ''): static
    {
        $this->actions[] = [
            'type' => 'button',
            'text' => $title,
            'url' => $url,
            'style' => $style,
        ];

        return $this;
    }

    /**
     * Set the author of the attachment
     *
     * @param string $name Author name
     * @param string|null $link Author link
     * @param string|null $icon Author icon
     * @return static
     */
    public function author(string $name, ?string $link = null, ?string $icon = null): static
    {
        $this->authorName = $name;
        $this->authorLink = $link;
        $this->authorIcon = $icon;

        return $this;
    }

    /**
     * Set the footer content
     *
     * @param string $footer Footer text
     * @return static
     */
    public function footer(string $footer): static
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Set the footer icon
     *
     * @param string $icon Footer icon URL
     * @return static
     */
    public function footerIcon(string $icon): static
    {
        $this->footerIcon = $icon;

        return $this;
    }

    /**
     * Set the timestamp
     *
     * @param int $timestamp Unix timestamp
     * @return static
     */
    public function timestamp(int $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Set the callback ID
     *
     * @param string $callbackId Callback ID
     * @return static
     */
    public function callbackId(string $callbackId): static
    {
        $this->callbackId = $callbackId;

        return $this;
    }

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Get URL
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Get pretext
     *
     * @return string|null
     */
    public function getPretext(): ?string
    {
        return $this->pretext;
    }

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Get fallback
     *
     * @return string|null
     */
    public function getFallback(): ?string
    {
        return $this->fallback;
    }

    /**
     * Get color
     *
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Get fields
     *
     * @return array<string|int, string|\Cake\SlackNotification\Message\SlackAttachmentField>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Get markdown fields
     *
     * @return array<string>
     */
    public function getMarkdown(): array
    {
        return $this->markdown;
    }

    /**
     * Get image URL
     *
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * Get thumb URL
     *
     * @return string|null
     */
    public function getThumbUrl(): ?string
    {
        return $this->thumbUrl;
    }

    /**
     * Get actions
     *
     * @return array<array<string, mixed>>
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Get author name
     *
     * @return string|null
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    /**
     * Get author link
     *
     * @return string|null
     */
    public function getAuthorLink(): ?string
    {
        return $this->authorLink;
    }

    /**
     * Get author icon
     *
     * @return string|null
     */
    public function getAuthorIcon(): ?string
    {
        return $this->authorIcon;
    }

    /**
     * Get footer
     *
     * @return string|null
     */
    public function getFooter(): ?string
    {
        return $this->footer;
    }

    /**
     * Get footer icon
     *
     * @return string|null
     */
    public function getFooterIcon(): ?string
    {
        return $this->footerIcon;
    }

    /**
     * Get timestamp
     *
     * @return int|null
     */
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    /**
     * Get callback ID
     *
     * @return string|null
     */
    public function getCallbackId(): ?string
    {
        return $this->callbackId;
    }
}
