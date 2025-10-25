<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Element\Select;

use Cake\SlackNotification\BlockKit\Composite\TextObject;
use Cake\Utility\Text;

/**
 * Select Option
 *
 * Represents an option in a select element for Slack Block Kit.
 *
 * @package Cake\SlackNotification\BlockKit\Element\Select
 */
class SelectOption
{
    /**
     * The option text.
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\TextObject
     */
    protected TextObject $text;

    /**
     * The option value.
     *
     * @var string
     */
    protected string $value;

    /**
     * Create a new select option instance.
     *
     * @param string $text Option text
     * @param mixed $value Option value
     */
    public function __construct(string $text, mixed $value)
    {
        $this->text($text);
        $this->value($value);
    }

    /**
     * Set the option's text value.
     *
     * @param string $text Text content
     * @return void
     */
    protected function text(string $text): void
    {
        $this->text = new TextObject($text, 75);
    }

    /**
     * Set the option's value.
     *
     * @param mixed $value Option value
     * @return void
     */
    protected function value(mixed $value): void
    {
        $this->value = (string)preg_replace('/[^a-z0-9_\-.]/', '', Text::slug((string)$value));
    }

    /**
     * Convert the select option to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text->toArray(),
            'value' => $this->value,
        ];
    }
}
