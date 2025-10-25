<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\BlockKit\Composite;

use Cake\SlackNotification\BlockKit\Composite\TextObject;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;

/**
 * TextObject Test Case
 */
class TextObjectTest extends TestCase
{
    /**
     * Test basic text object
     *
     * @return void
     */
    public function testBasicTextObject(): void
    {
        $object = new TextObject('A message *with some bold text*');

        $this->assertEquals([
            'type' => 'plain_text',
            'text' => 'A message *with some bold text*',
        ], $object->toArray());
    }

    /**
     * Test markdown text
     *
     * @return void
     */
    public function testMarkdownText(): void
    {
        $object = new TextObject('A message *with some bold text*');
        $object->markdown();

        $result = $object->toArray();

        $this->assertEquals('mrkdwn', $result['type']);
    }

    /**
     * Test emoji option
     *
     * @return void
     */
    public function testEmojiOption(): void
    {
        $object = new TextObject('Spooky time! 👻');
        $object->emoji();

        $result = $object->toArray();

        $this->assertTrue($result['emoji']);
    }

    /**
     * Test emoji not available with markdown
     *
     * @return void
     */
    public function testEmojiNotWithMarkdown(): void
    {
        $object = new TextObject('Spooky time! 👻');
        $object->markdown()->emoji();

        $result = $object->toArray();

        $this->assertArrayNotHasKey('emoji', $result);
    }

    /**
     * Test verbatim option
     *
     * @return void
     */
    public function testVerbatimOption(): void
    {
        $object = new TextObject('A message');
        $object->markdown()->verbatim();

        $result = $object->toArray();

        $this->assertTrue($result['verbatim']);
    }

    /**
     * Test verbatim not available with plain text
     *
     * @return void
     */
    public function testVerbatimNotWithPlainText(): void
    {
        $object = new TextObject('A message');
        $object->verbatim();

        $result = $object->toArray();

        $this->assertArrayNotHasKey('verbatim', $result);
    }

    /**
     * Test minimum length validation
     *
     * @return void
     */
    public function testMinimumLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum length for the text field is 1 characters.');

        new TextObject('');
    }

    /**
     * Test maximum length validation
     *
     * @return void
     */
    public function testMaximumLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the text field is 3000 characters.');

        new TextObject(str_repeat('a', 3001));
    }
}
