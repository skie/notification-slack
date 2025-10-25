<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Channel;

use Cake\Datasource\EntityInterface;
use Cake\Http\Client;
use Cake\Notification\AnonymousNotifiable;
use Cake\Notification\Channel\ChannelInterface;
use Cake\Notification\Exception\CouldNotSendNotification;
use Cake\Notification\Notification;
use Cake\SlackNotification\BlockKit\BlockKitMessage;
use Cake\SlackNotification\Message\SlackAttachment;
use Cake\SlackNotification\Message\SlackAttachmentField;
use Cake\SlackNotification\Message\SlackMessage;
use Exception;

/**
 * Slack Webhook Channel
 *
 * Sends notifications to Slack via incoming webhooks.
 * Uses webhook URLs (no OAuth token required).
 *
 * @link https://api.slack.com/messaging/webhooks
 */
class SlackWebhookChannel implements ChannelInterface
{
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

        if (is_string($message)) {
            $message = SlackMessage::create($message);
        }

        $webhookUrl = $this->getWebhookUrl($notifiable, $notification);
        if ($webhookUrl === null) {
            throw CouldNotSendNotification::missingRoutingInformation('slack');
        }

        try {
            $payload = $this->buildMessagePayload($message);

            $response = $this->client->post($webhookUrl, json_encode($payload), [
                'type' => 'json',
            ]);

            if (!$response->isOk()) {
                throw CouldNotSendNotification::serviceRespondedWithError(
                    'slack',
                    $response->getStringBody(),
                    "Slack API returned error: HTTP {$response->getStatusCode()}",
                );
            }

            return $response->getJson();
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
     * Get webhook URL
     *
     * @param \Cake\Datasource\EntityInterface|\Cake\Notification\AnonymousNotifiable $notifiable Notifiable entity
     * @param \Cake\Notification\Notification $notification Notification instance
     * @return string|null
     */
    protected function getWebhookUrl(
        EntityInterface|AnonymousNotifiable $notifiable,
        Notification $notification,
    ): ?string {
        if ($notifiable instanceof AnonymousNotifiable) {
            return $notifiable->routeNotificationFor('slack', $notification);
        }

        if (method_exists($notifiable, 'routeNotificationForSlack')) {
            return $notifiable->routeNotificationForSlack($notification);
        }

        if (isset($notifiable->slack_webhook_url)) {
            return $notifiable->slack_webhook_url;
        }

        return $this->config['webhook'] ?? null;
    }

    /**
     * Build JSON payload for Slack webhook
     *
     * @param \Cake\SlackNotification\Message\SlackMessage|\Cake\SlackNotification\BlockKit\BlockKitMessage $message Slack message
     * @return array<string, mixed>
     */
    protected function buildMessagePayload(SlackMessage|BlockKitMessage $message): array
    {
        if ($message instanceof BlockKitMessage) {
            return $message->toArray();
        }

        return $this->buildLegacyPayload($message);
    }

    /**
     * Build JSON payload for legacy Slack message
     *
     * @param \Cake\SlackNotification\Message\SlackMessage $message Slack message
     * @return array<string, mixed>
     */
    protected function buildLegacyPayload(SlackMessage $message): array
    {
        $payload = array_filter([
            'text' => $message->getContent(),
            'channel' => $message->getChannel(),
            'username' => $message->getUsername(),
            'icon_emoji' => $message->getIcon(),
            'icon_url' => $message->getImage(),
            'link_names' => $message->getLinkNames(),
            'unfurl_links' => $message->getUnfurlLinks(),
            'unfurl_media' => $message->getUnfurlMedia(),
        ], fn($value) => $value !== null && $value !== '');

        $attachments = $this->buildAttachments($message);
        if (!empty($attachments)) {
            $payload['attachments'] = $attachments;
        }

        return $payload;
    }

    /**
     * Build attachments array
     *
     * @param \Cake\SlackNotification\Message\SlackMessage $message Slack message
     * @return array<array<string, mixed>>
     */
    protected function buildAttachments(SlackMessage $message): array
    {
        $attachments = [];

        foreach ($message->getAttachments() as $attachment) {
            $attachments[] = $this->buildAttachment($attachment, $message);
        }

        return $attachments;
    }

    /**
     * Build single attachment
     *
     * @param \Cake\SlackNotification\Message\SlackAttachment $attachment Slack attachment
     * @param \Cake\SlackNotification\Message\SlackMessage $message Slack message
     * @return array<string, mixed>
     */
    protected function buildAttachment(SlackAttachment $attachment, SlackMessage $message): array
    {
        return array_filter([
            'title' => $attachment->getTitle(),
            'title_link' => $attachment->getUrl(),
            'pretext' => $attachment->getPretext(),
            'text' => $attachment->getContent(),
            'fallback' => $attachment->getFallback(),
            'color' => $attachment->getColor() ?: $message->getColor(),
            'fields' => $this->buildFields($attachment),
            'mrkdwn_in' => !empty($attachment->getMarkdown()) ? $attachment->getMarkdown() : null,
            'image_url' => $attachment->getImageUrl(),
            'thumb_url' => $attachment->getThumbUrl(),
            'actions' => !empty($attachment->getActions()) ? $attachment->getActions() : null,
            'author_name' => $attachment->getAuthorName(),
            'author_link' => $attachment->getAuthorLink(),
            'author_icon' => $attachment->getAuthorIcon(),
            'footer' => $attachment->getFooter(),
            'footer_icon' => $attachment->getFooterIcon(),
            'ts' => $attachment->getTimestamp(),
            'callback_id' => $attachment->getCallbackId(),
        ], fn($value) => $value !== null);
    }

    /**
     * Build fields array
     *
     * @param \Cake\SlackNotification\Message\SlackAttachment $attachment Slack attachment
     * @return array<array<string, mixed>>|null
     */
    protected function buildFields(SlackAttachment $attachment): ?array
    {
        $fields = [];

        foreach ($attachment->getFields() as $key => $value) {
            if ($value instanceof SlackAttachmentField) {
                $fields[] = $value->toArray();
            } elseif (is_string($key)) {
                $fields[] = [
                    'title' => $key,
                    'value' => $value,
                    'short' => true,
                ];
            }
        }

        return !empty($fields) ? $fields : null;
    }
}
