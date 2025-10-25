<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\Channel;

use Cake\Datasource\EntityInterface;
use Cake\Http\Client;
use Cake\Http\Client\Response;
use Cake\Notification\Exception\CouldNotSendNotification;
use Cake\Notification\Notification;
use Cake\SlackNotification\Channel\SlackWebhookChannel;
use Cake\SlackNotification\Message\SlackMessage;
use Cake\TestSuite\TestCase;

/**
 * SlackWebhookChannel Test Case
 */
class SlackChannelTest extends TestCase
{
    /**
     * Test send with string message
     *
     * @return void
     */
    public function testSendWithStringMessage(): void
    {
        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('isOk')->willReturn(true);
        $mockResponse->method('getJson')->willReturn(['ok' => true]);

        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('post')
            ->willReturn($mockResponse);

        $channel = new SlackWebhookChannel(['webhook' => 'https://hooks.slack.com/test'], $mockClient);

        $entity = $this->createMock(EntityInterface::class);

        $notification = $this->createMock(TestSlackNotification::class);
        $notification->method('toSlack')->willReturn('Test message');

        $result = $channel->send($entity, $notification);
        $this->assertEquals(['ok' => true], $result);
    }

    /**
     * Test send returns null when notification does not have toSlack method
     *
     * @return void
     */
    public function testSendReturnsNullWithoutToSlackMethod(): void
    {
        $channel = new SlackWebhookChannel(['webhook' => 'https://hooks.slack.com/test']);

        $entity = $this->createMock(EntityInterface::class);
        $notification = $this->createMock(Notification::class);

        $result = $channel->send($entity, $notification);
        $this->assertNull($result);
    }

    /**
     * Test missing webhook URL
     *
     * @return void
     */
    public function testMissingWebhookUrl(): void
    {
        $channel = new SlackWebhookChannel([]);

        $entity = $this->createMock(EntityInterface::class);

        $notification = $this->createMock(TestSlackNotification::class);
        $notification->method('toSlack')->willReturn(SlackMessage::create('Test'));

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage("Channel 'slack' requires routing information");

        $channel->send($entity, $notification);
    }
}
