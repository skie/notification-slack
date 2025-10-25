<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\BlockKit\Block;

use Cake\SlackNotification\BlockKit\Block\SectionBlock;
use Cake\SlackNotification\BlockKit\Element\ImageElement;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use LogicException;

/**
 * SectionBlock Test Case
 */
class SectionBlockTest extends TestCase
{
    /**
     * Test basic section with text
     *
     * @return void
     */
    public function testBasicSection(): void
    {
        $block = new SectionBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010');

        $result = $block->toArray();

        $this->assertEquals('section', $result['type']);
        $this->assertEquals('plain_text', $result['text']['type']);
        $this->assertEquals('Location: 123 Main Street, New York, NY 10010', $result['text']['text']);
    }

    /**
     * Test section requires text or fields
     *
     * @return void
     */
    public function testSectionRequiresTextOrFields(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('A section requires at least the text or fields to be set.');

        $block = new SectionBlock();
        $block->toArray();
    }

    /**
     * Test section with markdown
     *
     * @return void
     */
    public function testSectionWithMarkdown(): void
    {
        $block = new SectionBlock();
        $block->text('Location: *123 Main Street*, New York')->markdown();

        $result = $block->toArray();

        $this->assertEquals('mrkdwn', $result['text']['type']);
    }

    /**
     * Test section with fields
     *
     * @return void
     */
    public function testSectionWithFields(): void
    {
        $block = new SectionBlock();
        $block->field('Field 1');
        $block->field('Field 2');

        $result = $block->toArray();

        $this->assertCount(2, $result['fields']);
        $this->assertEquals('Field 1', $result['fields'][0]['text']);
    }

    /**
     * Test maximum 10 fields
     *
     * @return void
     */
    public function testMaximumTenFields(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('There is a maximum of 10 fields in each section block.');

        $block = new SectionBlock();
        for ($i = 0; $i < 11; $i++) {
            $block->field('Field ' . $i);
        }

        $block->toArray();
    }

    /**
     * Test section with accessory
     *
     * @return void
     */
    public function testSectionWithAccessory(): void
    {
        $block = new SectionBlock();
        $block->text('Location: 123 Main Street');
        $block->accessory(new ImageElement('https://example.com/image.png', 'Image'));

        $result = $block->toArray();

        $this->assertArrayHasKey('accessory', $result);
        $this->assertEquals('image', $result['accessory']['type']);
    }

    /**
     * Test block ID max length
     *
     * @return void
     */
    public function testBlockIdMaxLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the block_id field is 255 characters.');

        $block = new SectionBlock();
        $block->text('Test');
        $block->id(str_repeat('a', 256));

        $block->toArray();
    }
}
