<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\Channel;

use Cake\Datasource\EntityInterface;
use Cake\Notification\AnonymousNotifiable;
use Cake\Notification\Notification;

/**
 * Test Slack Notification
 */
class TestSlackNotification extends Notification
{
    /**
     * @inheritDoc
     */
    public function via(EntityInterface|AnonymousNotifiable $notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get Slack message
     *
     * @param \Cake\Datasource\EntityInterface|\Cake\Notification\AnonymousNotifiable $notifiable Notifiable entity
     * @return mixed
     */
    public function toSlack(EntityInterface|AnonymousNotifiable $notifiable): mixed
    {
        return null;
    }
}
