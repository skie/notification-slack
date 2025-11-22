<?php
declare(strict_types=1);

namespace Cake\SlackNotification;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManager;
use Cake\SlackNotification\Provider\SlackChannelProvider;

/**
 * Slack Notification Plugin
 *
 * Registers the Slack notification channel with the CakePHP Notification plugin.
 */
class SlackNotificationPlugin extends BasePlugin
{
    /**
     * Bootstrap hook
     *
     * Registers the Slack channel with the notification registry.
     *
     * @param \Cake\Core\PluginApplicationInterface<\Cake\Core\PluginInterface> $app Application instance
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        EventManager::instance()->on(
            'Notification.Registry.discover',
            function ($event): void {
                $registry = $event->getSubject();
                (new SlackChannelProvider())->register($registry);
            },
        );
    }
}
