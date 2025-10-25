<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\Channel;

use Cake\Datasource\EntityInterface;
use Cake\Http\Client;
use Cake\Http\Client\Response;
use Cake\SlackNotification\BlockKit\BlockKitMessage;
use Cake\SlackNotification\Channel\SlackWebApiChannel;
use Cake\TestSuite\TestCase;
use LogicException;

/**
 * SlackWebApiChannel Test Case
 */
class SlackWebApiChannelTest extends TestCase
{
    /**
     * Test send with BlockKit message
     *
     * @return void
     */
    public function testSendWithBlockKitMessage(): void
    {
        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('isOk')->willReturn(true);
        $mockResponse->method('isSuccess')->willReturn(true);
        $mockResponse->method('getJson')->willReturn(['ok' => true, 'ts' => '1234567890.123456']);

        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('post')
            ->with(
                SlackWebApiChannel::API_ENDPOINT,
                $this->anything(),
                $this->callback(function ($options) {
                    return isset($options['headers']['Authorization'])
                        && str_contains($options['headers']['Authorization'], 'Bearer ');
                }),
            )
            ->willReturn($mockResponse);

        $channel = new SlackWebApiChannel(['bot_user_oauth_token' => 'xoxb-test-token'], $mockClient);

        $entity = new TestRoutableEntity();
        $entity->setRouteInfo('#general');

        $notification = $this->createMock(TestSlackWebApiNotification::class);
        $message = new BlockKitMessage();
        $message->text('Test message')->to('#general');
        $notification->method('toSlack')->willReturn($message);

        $result = $channel->send($entity, $notification);

        $this->assertEquals(['ok' => true, 'ts' => '1234567890.123456'], $result);
    }

    /**
     * Test missing OAuth token
     *
     * @return void
     */
    public function testMissingOAuthToken(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Slack API authentication token is not set.');

        $channel = new SlackWebApiChannel([]);

        $entity = new TestRoutableEntity();
        $entity->setRouteInfo('#general');

        $notification = $this->createMock(TestSlackWebApiNotification::class);
        $message = new BlockKitMessage();
        $message->text('Test')->to('#general');
        $notification->method('toSlack')->willReturn($message);

        $channel->send($entity, $notification);
    }

    /**
     * Test missing channel
     *
     * @return void
     */
    public function testMissingChannel(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Slack notification channel is not set.');

        $channel = new SlackWebApiChannel(['bot_user_oauth_token' => 'xoxb-test']);

        $entity = $this->createMock(EntityInterface::class);

        $notification = $this->createMock(TestSlackWebApiNotification::class);
        $message = new BlockKitMessage();
        $message->text('Test');
        $notification->method('toSlack')->willReturn($message);

        $channel->send($entity, $notification);
    }
}
