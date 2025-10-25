<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Provider;

use Cake\Core\Configure;
use Cake\Notification\Extension\ChannelProviderInterface;
use Cake\Notification\Registry\ChannelRegistry;
use Cake\SlackNotification\Channel\SlackWebApiChannel;
use Cake\SlackNotification\Channel\SlackWebhookChannel;

/**
 * Slack Channel Provider
 *
 * Registers the Slack channel with the notification system.
 * Automatically selects the appropriate channel based on configuration.
 */
class SlackChannelProvider implements ChannelProviderInterface
{
    /**
     * @inheritDoc
     */
    public function provides(): array
    {
        return ['slack'];
    }

    /**
     * @inheritDoc
     */
    public function register(ChannelRegistry $registry): void
    {
        $config = array_merge(
            $this->getDefaultConfig(),
            (array)Configure::read('Notification.channels.slack', []),
        );

        $channelClass = $this->determineChannelClass($config);

        $registry->load('slack', [
            'className' => $channelClass,
        ] + $config);
    }

    /**
     * Determine which channel class to use
     *
     * @param array<string, mixed> $config Configuration
     * @return class-string
     */
    protected function determineChannelClass(array $config): string
    {
        if (isset($config['className'])) {
            return $config['className'];
        }

        if (isset($config['bot_user_oauth_token']) || isset($config['token'])) {
            return SlackWebApiChannel::class;
        }

        return SlackWebhookChannel::class;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultConfig(): array
    {
        $webhook = getenv('SLACK_WEBHOOK_URL');
        $token = getenv('SLACK_BOT_TOKEN');

        return array_filter([
            'webhook' => $webhook !== false ? $webhook : null,
            'bot_user_oauth_token' => $token !== false ? $token : null,
        ], fn($value) => $value !== null);
    }
}
