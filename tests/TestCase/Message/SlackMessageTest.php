<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\Message;

use Cake\SlackNotification\Message\SlackMessage;
use Cake\TestSuite\TestCase;

/**
 * SlackMessage Test Case
 */
class SlackMessageTest extends TestCase
{
    /**
     * Test create method
     *
     * @return void
     */
    public function testCreate(): void
    {
        $message = SlackMessage::create('Test message');
        $this->assertInstanceOf(SlackMessage::class, $message);
        $this->assertEquals('Test message', $message->getContent());
    }

    /**
     * Test level methods
     *
     * @return void
     */
    public function testLevels(): void
    {
        $message = SlackMessage::create()->success();
        $this->assertEquals('good', $message->getColor());

        $message = SlackMessage::create()->error();
        $this->assertEquals('danger', $message->getColor());

        $message = SlackMessage::create()->warning();
        $this->assertEquals('warning', $message->getColor());

        $message = SlackMessage::create()->info();
        $this->assertNull($message->getColor());
    }

    /**
     * Test fluent interface
     *
     * @return void
     */
    public function testFluentInterface(): void
    {
        $message = SlackMessage::create()
            ->content('Test')
            ->from('Bot', ':robot:')
            ->to('#general')
            ->success();

        $this->assertEquals('Test', $message->getContent());
        $this->assertEquals('Bot', $message->getUsername());
        $this->assertEquals(':robot:', $message->getIcon());
        $this->assertEquals('#general', $message->getChannel());
    }

    /**
     * Test attachment method
     *
     * @return void
     */
    public function testAttachment(): void
    {
        $message = SlackMessage::create()->attachment(function ($attachment): void {
            $attachment->title('Test');
        });

        $this->assertCount(1, $message->getAttachments());
    }
}
