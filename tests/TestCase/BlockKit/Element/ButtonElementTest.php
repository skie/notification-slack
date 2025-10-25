<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\BlockKit\Element;

use Cake\SlackNotification\BlockKit\Element\ButtonElement;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;

/**
 * ButtonElement Test Case
 */
class ButtonElementTest extends TestCase
{
    /**
     * Test basic button
     *
     * @return void
     */
    public function testBasicButton(): void
    {
        $element = new ButtonElement('Click Me');

        $result = $element->toArray();

        $this->assertEquals('button', $result['type']);
        $this->assertEquals('Click Me', $result['text']['text']);
        $this->assertStringStartsWith('button_', $result['action_id']);
    }

    /**
     * Test button with URL
     *
     * @return void
     */
    public function testButtonWithUrl(): void
    {
        $element = new ButtonElement('Click Me');
        $element->url('https://example.com');

        $result = $element->toArray();

        $this->assertEquals('https://example.com', $result['url']);
    }

    /**
     * Test button with value
     *
     * @return void
     */
    public function testButtonWithValue(): void
    {
        $element = new ButtonElement('Click Me');
        $element->value('click_me_123');

        $result = $element->toArray();

        $this->assertEquals('click_me_123', $result['value']);
    }

    /**
     * Test primary style
     *
     * @return void
     */
    public function testPrimaryStyle(): void
    {
        $element = new ButtonElement('Click Me');
        $element->primary();

        $result = $element->toArray();

        $this->assertEquals('primary', $result['style']);
    }

    /**
     * Test danger style
     *
     * @return void
     */
    public function testDangerStyle(): void
    {
        $element = new ButtonElement('Click Me');
        $element->danger();

        $result = $element->toArray();

        $this->assertEquals('danger', $result['style']);
    }

    /**
     * Test with confirmation dialog
     *
     * @return void
     */
    public function testWithConfirmation(): void
    {
        $element = new ButtonElement('Click Me');
        $element->confirm('Are you sure?');

        $result = $element->toArray();

        $this->assertArrayHasKey('confirm', $result);
        $this->assertEquals('Are you sure?', $result['confirm']['text']['text']);
    }

    /**
     * Test custom action ID
     *
     * @return void
     */
    public function testCustomActionId(): void
    {
        $element = new ButtonElement('Click Me');
        $element->id('custom_id');

        $result = $element->toArray();

        $this->assertEquals('custom_id', $result['action_id']);
    }

    /**
     * Test action ID max length
     *
     * @return void
     */
    public function testActionIdMaxLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the action_id field is 255 characters.');

        $element = new ButtonElement('Click Me');
        $element->id(str_repeat('a', 256));

        $element->toArray();
    }

    /**
     * Test URL max length
     *
     * @return void
     */
    public function testUrlMaxLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the url field is 3000 characters.');

        $element = new ButtonElement('Click Me');
        $element->url(str_repeat('a', 3001));

        $element->toArray();
    }

    /**
     * Test value max length
     *
     * @return void
     */
    public function testValueMaxLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the value field is 2000 characters.');

        $element = new ButtonElement('Click Me');
        $element->value(str_repeat('a', 2001));

        $element->toArray();
    }
}
