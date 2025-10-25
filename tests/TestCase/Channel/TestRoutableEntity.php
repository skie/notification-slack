<?php
declare(strict_types=1);

namespace Cake\SlackNotification\Test\TestCase\Channel;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\EntityTrait;
use Cake\Notification\Notification;

/**
 * Test Routable Entity
 */
class TestRoutableEntity implements EntityInterface
{
    use EntityTrait;

    /**
     * Route info
     *
     * @var string|null
     */
    protected ?string $routeInfo = null;

    /**
     * Set route info
     *
     * @param string|null $info Route info
     * @return void
     */
    public function setRouteInfo(?string $info): void
    {
        $this->routeInfo = $info;
    }

    /**
     * Route notification for Slack
     *
     * @param \Cake\Notification\Notification|null $notification Notification
     * @return string|null
     */
    public function routeNotificationForSlack(?Notification $notification = null): ?string
    {
        return $this->routeInfo;
    }
}
