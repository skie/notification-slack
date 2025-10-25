<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\BlockKit;

use Cake\SlackNotification\BlockKit\BlockKitMessage;
use Cake\TestSuite\TestCase;

/**
 * BlockKitMessage Test Case
 */
class BlockKitMessageTest extends TestCase
{
    /**
     * Test basic message creation
     *
     * @return void
     */
    public function testBasicMessage(): void
    {
        $message = new BlockKitMessage();
        $message->text('Hello World')
            ->to('#general');

        $array = $message->toArray();

        $this->assertEquals('Hello World', $array['text']);
        $this->assertEquals('#general', $array['channel']);
    }

    /**
     * Test header block
     *
     * @return void
     */
    public function testHeaderBlock(): void
    {
        $message = new BlockKitMessage();
        $message->text('Fallback')
            ->headerBlock('My Header');

        $array = $message->toArray();

        $this->assertCount(1, $array['blocks']);
        $this->assertEquals('header', $array['blocks'][0]['type']);
        $this->assertEquals('My Header', $array['blocks'][0]['text']['text']);
    }

    /**
     * Test section block
     *
     * @return void
     */
    public function testSectionBlock(): void
    {
        $message = new BlockKitMessage();
        $message->text('Fallback')
            ->sectionBlock(function ($section): void {
                $section->text('Section text');
            });

        $array = $message->toArray();

        $this->assertCount(1, $array['blocks']);
        $this->assertEquals('section', $array['blocks'][0]['type']);
    }

    /**
     * Test divider block
     *
     * @return void
     */
    public function testDividerBlock(): void
    {
        $message = new BlockKitMessage();
        $message->text('Fallback')
            ->dividerBlock();

        $array = $message->toArray();

        $this->assertCount(1, $array['blocks']);
        $this->assertEquals('divider', $array['blocks'][0]['type']);
    }

    /**
     * Test actions block with button
     *
     * @return void
     */
    public function testActionsBlockWithButton(): void
    {
        $message = new BlockKitMessage();
        $message->text('Fallback')
            ->actionsBlock(function ($actions): void {
                $actions->button('Click Me')
                    ->url('https://example.com')
                    ->primary();
            });

        $array = $message->toArray();

        $this->assertCount(1, $array['blocks']);
        $this->assertEquals('actions', $array['blocks'][0]['type']);
        $this->assertCount(1, $array['blocks'][0]['elements']);
        $this->assertEquals('button', $array['blocks'][0]['elements'][0]['type']);
        $this->assertEquals('Click Me', $array['blocks'][0]['elements'][0]['text']['text']);
        $this->assertEquals('primary', $array['blocks'][0]['elements'][0]['style']);
    }
}
