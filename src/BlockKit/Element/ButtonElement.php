<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Element;

use Cake\SlackNotification\BlockKit\Composite\ConfirmObject;
use Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject;
use Cake\SlackNotification\BlockKit\Element\Trait\GeneratesDefaultIdsTrait;
use Closure;
use InvalidArgumentException;

/**
 * Button Element
 *
 * An interactive button element.
 */
class ButtonElement implements ElementInterface, AccessoryInterface
{
    use GeneratesDefaultIdsTrait;

    public const STYLE_PRIMARY = 'primary';
    public const STYLE_DANGER = 'danger';

    /**
     * Button text
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject
     */
    protected PlainTextOnlyTextObject $text;

    /**
     * Action ID
     *
     * @var string
     */
    protected string $actionId;

    /**
     * Button URL
     *
     * @var string|null
     */
    protected ?string $url = null;

    /**
     * Button value
     *
     * @var string|null
     */
    protected ?string $value = null;

    /**
     * Button style
     *
     * @var string|null
     */
    protected ?string $style = null;

    /**
     * Confirmation dialog
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\ConfirmObject|null
     */
    protected ?ConfirmObject $confirm = null;

    /**
     * Accessibility label
     *
     * @var string|null
     */
    protected ?string $accessibilityLabel = null;

    /**
     * Constructor
     *
     * @param string $text Button text (max 75 chars)
     * @param \Closure|null $callback Optional callback to configure text object
     */
    public function __construct(string $text, ?Closure $callback = null)
    {
        $this->text = new PlainTextOnlyTextObject($text, 75);
        $this->id($this->resolveDefaultId('button_', $text));

        if ($callback !== null) {
            $callback($this->text);
        }
    }

    /**
     * Set the button URL
     *
     * @param string $url URL (max 3000 chars)
     * @return static
     */
    public function url(string $url): static
    {
        if (strlen($url) > 3000) {
            throw new InvalidArgumentException('Maximum length for the url field is 3000 characters.');
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Set the action ID
     *
     * @param string $id Action ID (max 255 chars)
     * @return static
     */
    public function id(string $id): static
    {
        if (strlen($id) > 255) {
            throw new InvalidArgumentException('Maximum length for the action_id field is 255 characters.');
        }

        $this->actionId = $id;

        return $this;
    }

    /**
     * Set the button value
     *
     * @param string $value Value (max 2000 chars)
     * @return static
     */
    public function value(string $value): static
    {
        if (strlen($value) > 2000) {
            throw new InvalidArgumentException('Maximum length for the value field is 2000 characters.');
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Set button style to primary
     *
     * @return static
     */
    public function primary(): static
    {
        $this->style = self::STYLE_PRIMARY;

        return $this;
    }

    /**
     * Set button style to danger
     *
     * @return static
     */
    public function danger(): static
    {
        $this->style = self::STYLE_DANGER;

        return $this;
    }

    /**
     * Add confirmation dialog
     *
     * @param string $text Confirmation text
     * @param \Closure|null $callback Optional callback to configure confirm object
     * @return \Cake\SlackNotification\BlockKit\Composite\ConfirmObject
     */
    public function confirm(string $text, ?Closure $callback = null): ConfirmObject
    {
        $this->confirm = $confirm = new ConfirmObject($text);

        if ($callback !== null) {
            $callback($confirm);
        }

        return $confirm;
    }

    /**
     * Set accessibility label
     *
     * @param string $label Label (max 75 chars)
     * @return static
     */
    public function accessibilityLabel(string $label): static
    {
        if (strlen($label) > 75) {
            throw new InvalidArgumentException('Maximum length for the accessibility label is 75 characters.');
        }

        $this->accessibilityLabel = $label;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $optionalFields = array_filter([
            'url' => $this->url,
            'value' => $this->value,
            'style' => $this->style,
            'confirm' => $this->confirm?->toArray(),
            'accessibility_label' => $this->accessibilityLabel,
        ], fn($value) => $value !== null);

        return array_merge([
            'type' => 'button',
            'text' => $this->text->toArray(),
            'action_id' => $this->actionId,
        ], $optionalFields);
    }
}
