<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Channel;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Http\Client;
use Cake\Notification\AnonymousNotifiable;
use Cake\Notification\Channel\ChannelInterface;
use Cake\Notification\Exception\CouldNotSendNotification;
use Cake\Notification\Notification;
use Cake\SlackNotification\BlockKit\BlockKitMessage;
use Cake\SlackNotification\BlockKit\SlackRoute;
use Exception;
use LogicException;
use RuntimeException;

/**
 * Slack Web API Channel
 *
 * Sends notifications to Slack via Web API (chat.postMessage).
 * Requires OAuth bot token for authentication.
 */
class SlackWebApiChannel implements ChannelInterface
{
    /**
     * Slack Web API endpoint
     */
    public const API_ENDPOINT = 'https://slack.com/api/chat.postMessage';

    /**
     * HTTP client
     *
     * @var \Cake\Http\Client
     */
    protected Client $client;

    /**
     * Constructor
     *
     * @param array<string, mixed> $config Channel configuration
     * @param \Cake\Http\Client|null $client HTTP client for testing
     */
    public function __construct(
        protected array $config = [],
        ?Client $client = null,
    ) {
        $this->client = $client ?? new Client();
    }

    /**
     * @inheritDoc
     */
    public function send(EntityInterface|AnonymousNotifiable $notifiable, Notification $notification): mixed
    {
        if (!method_exists($notification, 'toSlack')) {
            return null;
        }

        $message = $notification->toSlack($notifiable);

        if ($message === null) {
            return null;
        }

        $route = $this->determineRoute($notifiable, $notification);

        $payload = $this->buildJsonPayload($message, $route);

        if (empty($payload['channel'])) {
            throw new LogicException('Slack notification channel is not set.');
        }

        if (empty($route->token)) {
            throw new LogicException('Slack API authentication token is not set.');
        }

        try {
            $response = $this->client->post(self::API_ENDPOINT, json_encode($payload), [
                'type' => 'json',
                'headers' => [
                    'Authorization' => 'Bearer ' . $route->token,
                ],
            ]);

            if (!$response->isOk()) {
                throw CouldNotSendNotification::serviceRespondedWithError(
                    'slack',
                    $response->getStringBody(),
                    "Slack API returned error: HTTP {$response->getStatusCode()}",
                );
            }

            $data = $response->getJson();

            if ($response->isSuccess() && isset($data['ok']) && $data['ok'] === false) {
                $error = $data['error'] ?? 'unknown error';
                throw new RuntimeException("Slack API call failed with error [{$error}].");
            }

            return $data;
        } catch (CouldNotSendNotification $e) {
            throw $e;
        } catch (Exception $e) {
            throw CouldNotSendNotification::serviceRespondedWithError(
                'slack',
                $e->getMessage(),
                "Failed to send Slack notification: {$e->getMessage()}",
            );
        }
    }

    /**
     * Build JSON payload for Slack Web API
     *
     * @param \Cake\SlackNotification\BlockKit\BlockKitMessage $message Slack message
     * @param \Cake\SlackNotification\BlockKit\SlackRoute $route Slack route
     * @return array<string, mixed>
     */
    protected function buildJsonPayload(BlockKitMessage $message, SlackRoute $route): array
    {
        $payload = $message->toArray();

        $defaultChannel = Configure::read('Notification.channels.slack.channel');

        return array_merge($payload, [
            'channel' => $route->channel ?? $payload['channel'] ?? $defaultChannel,
        ]);
    }

    /**
     * Determine the API token and channel
     *
     * @param \Cake\Datasource\EntityInterface|\Cake\Notification\AnonymousNotifiable $notifiable Notifiable entity
     * @param \Cake\Notification\Notification $notification Notification instance
     * @return \Cake\SlackNotification\BlockKit\SlackRoute
     */
    protected function determineRoute(
        EntityInterface|AnonymousNotifiable $notifiable,
        Notification $notification,
    ): SlackRoute {
        if ($notifiable instanceof AnonymousNotifiable) {
            $route = $notifiable->routeNotificationFor('slack', $notification);
        } elseif (method_exists($notifiable, 'routeNotificationForSlack')) {
            $route = $notifiable->routeNotificationForSlack($notification);
        } else {
            $route = null;
        }

        $defaultToken = $this->config['bot_user_oauth_token']
            ?? Configure::read('Notification.channels.slack.bot_user_oauth_token');

        if (is_string($route)) {
            return SlackRoute::new($route, $defaultToken);
        }

        if ($route instanceof SlackRoute) {
            return SlackRoute::new(
                $route->channel ?? null,
                $route->token ?? $defaultToken,
            );
        }

        return SlackRoute::new(null, $defaultToken);
    }
}
