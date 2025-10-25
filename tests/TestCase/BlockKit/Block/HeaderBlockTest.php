<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\BlockKit\Block;

use Cake\SlackNotification\BlockKit\Block\HeaderBlock;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;

/**
 * HeaderBlock Test Case
 */
class HeaderBlockTest extends TestCase
{
    /**
     * Test basic header block
     *
     * @return void
     */
    public function testBasicHeaderBlock(): void
    {
        $block = new HeaderBlock('Budget Performance');

        $this->assertEquals([
            'type' => 'header',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Budget Performance',
            ],
        ], $block->toArray());
    }

    /**
     * Test header with block ID
     *
     * @return void
     */
    public function testHeaderWithBlockId(): void
    {
        $block = new HeaderBlock('Budget Performance');
        $block->id('header1');

        $this->assertEquals([
            'type' => 'header',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Budget Performance',
            ],
            'block_id' => 'header1',
        ], $block->toArray());
    }

    /**
     * Test block ID cannot exceed 255 characters
     *
     * @return void
     */
    public function testBlockIdMaxLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the block_id field is 255 characters.');

        $block = new HeaderBlock('Budget Performance');
        $block->id(str_repeat('a', 256));

        $block->toArray();
    }

    /**
     * Test text max length validation
     *
     * @return void
     */
    public function testTextMaxLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the text field is 150 characters.');

        new HeaderBlock(str_repeat('a', 151));
    }
}
