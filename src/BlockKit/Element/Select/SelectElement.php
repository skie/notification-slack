<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Element\Select;

use Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject;
use Cake\SlackNotification\BlockKit\Element\AccessoryInterface;
use InvalidArgumentException;

/**
 * Select Element
 *
 * Abstract base class for select elements in Slack Block Kit.
 *
 * @package Cake\SlackNotification\BlockKit\Element\Select
 */
abstract class SelectElement implements AccessoryInterface
{
    /**
     * An identifier for this action.
     *
     * You can use this when you receive an interaction payload to identify the source of the action.
     *
     * Should be unique among all other action_ids in the containing block.
     *
     * Maximum length for this field is 255 characters.
     *
     * @var string
     */
    protected string $actionId;

    /**
     * A text object that defines the select's text.
     *
     * Can only be of type: plain_text. Text may truncate with ~30 characters.
     *
     * Maximum length for the text in this field is 75 characters.
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject|null
     */
    protected ?PlainTextOnlyTextObject $placeholder = null;

    /**
     * Indicates whether the element should automatically gain focus when the view loads.
     *
     * When set to `true`, this element will automatically receive focus in the UI.
     * Useful for prioritizing user interaction.
     *
     * @var bool|null
     */
    protected ?bool $focusOnLoad = null;

    /**
     * Set the action ID for the select.
     *
     * @param string $id Action ID
     * @return static
     * @throws \InvalidArgumentException
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
     * Set the placeholder text.
     *
     * @param string $text Placeholder text
     * @return static
     */
    public function placeholder(string $text): static
    {
        $this->placeholder = new PlainTextOnlyTextObject($text);

        return $this;
    }

    /**
     * Set whether the element should automatically gain focus when the view loads.
     *
     * @param bool $focus Whether to focus on load
     * @return static
     */
    public function focus(bool $focus = true): static
    {
        $this->focusOnLoad = $focus;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'action_id' => $this->actionId,
            'placeholder' => $this->placeholder?->toArray(),
            'focus_on_load' => $this->focusOnLoad,
        ], static fn($value): bool => $value !== null);
    }
}
