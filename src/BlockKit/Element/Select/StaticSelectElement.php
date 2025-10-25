<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Element\Select;

use Cake\SlackNotification\BlockKit\Element\Trait\GeneratesDefaultIdsTrait;
use InvalidArgumentException;

/**
 * Static Select Element
 *
 * A select menu with static options for Slack Block Kit.
 *
 * @package Cake\SlackNotification\BlockKit\Element\Select
 */
class StaticSelectElement extends SelectElement
{
    use GeneratesDefaultIdsTrait;

    /**
     * The select element options.
     *
     * @var array<string, \Cake\SlackNotification\BlockKit\Element\Select\SelectOption>
     */
    private array $options = [];

    /**
     * The initially selected option, if applicable.
     *
     * @var \Cake\SlackNotification\BlockKit\Element\Select\SelectOption|null
     */
    private ?SelectOption $initialOption = null;

    /**
     * Create a new static select element instance.
     */
    public function __construct()
    {
        $this->id($this->resolveDefaultId('static_select_', 'element'));
    }

    /**
     * Add an option to the select element.
     *
     * @param string $text Option text
     * @param string $value Option value
     * @return static
     */
    public function addOption(string $text, string $value): static
    {
        $this->options[$value] = new SelectOption($text, $value);

        return $this;
    }

    /**
     * Set the default selected option for the select element.
     *
     * @param string $value Option value to select by default
     * @return static
     * @throws \InvalidArgumentException
     */
    public function initialOption(string $value): static
    {
        $option = $this->options[$value] ?? null;

        if ($option === null) {
            throw new InvalidArgumentException("Unknown option value: $value.");
        }

        $this->initialOption = $option;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $options = array_values($this->options);

        $options = array_map(fn(SelectOption $option) => $option->toArray(), $options);

        return array_filter(array_merge([
            'type' => 'static_select',
            'options' => $options,
            'initial_option' => $this->initialOption?->toArray(),
        ], parent::toArray()), fn($value): bool => $value !== null);
    }
}
