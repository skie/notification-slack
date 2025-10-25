<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit\Composite;

/**
 * Confirm Object
 *
 * Defines a confirmation dialog for interactive elements.
 */
class ConfirmObject implements ObjectInterface
{
    /**
     * Dialog title
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject
     */
    protected PlainTextOnlyTextObject $title;

    /**
     * Explanatory text
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\TextObject
     */
    protected TextObject $text;

    /**
     * Confirm button label
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject
     */
    protected PlainTextOnlyTextObject $confirm;

    /**
     * Deny button label
     *
     * @var \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject
     */
    protected PlainTextOnlyTextObject $deny;

    /**
     * Button style
     *
     * @var string|null
     */
    protected ?string $style = null;

    /**
     * Constructor
     *
     * @param string $text Confirmation text
     */
    public function __construct(string $text = 'Please confirm this action.')
    {
        $this->title('Are you sure?');
        $this->text($text);
        $this->confirm('Yes');
        $this->deny('No');
    }

    /**
     * Set the dialog title
     *
     * @param string $title Title text (max 100 chars)
     * @return \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject
     */
    public function title(string $title): PlainTextOnlyTextObject
    {
        $this->title = $object = new PlainTextOnlyTextObject($title, 100);

        return $object;
    }

    /**
     * Set the explanatory text
     *
     * @param string $text Explanatory text (max 300 chars)
     * @return \Cake\SlackNotification\BlockKit\Composite\TextObject
     */
    public function text(string $text): TextObject
    {
        $this->text = $object = new TextObject($text, 300);

        return $object;
    }

    /**
     * Set the confirm button label
     *
     * @param string $label Button label (max 30 chars)
     * @return \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject
     */
    public function confirm(string $label): PlainTextOnlyTextObject
    {
        $this->confirm = $object = new PlainTextOnlyTextObject($label, 30);

        return $object;
    }

    /**
     * Set the deny button label
     *
     * @param string $label Button label (max 30 chars)
     * @return \Cake\SlackNotification\BlockKit\Composite\PlainTextOnlyTextObject
     */
    public function deny(string $label): PlainTextOnlyTextObject
    {
        $this->deny = $object = new PlainTextOnlyTextObject($label, 30);

        return $object;
    }

    /**
     * Mark the confirm dialog as dangerous
     *
     * @return static
     */
    public function danger(): static
    {
        $this->style = 'danger';

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $optionalFields = array_filter([
            'style' => $this->style,
        ], fn($value) => $value !== null);

        return array_merge([
            'title' => $this->title->toArray(),
            'text' => $this->text->toArray(),
            'confirm' => $this->confirm->toArray(),
            'deny' => $this->deny->toArray(),
        ], $optionalFields);
    }
}
