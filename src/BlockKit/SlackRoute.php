<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit;

/**
 * Slack Route
 *
 * Defines routing information for Slack notifications.
 */
class SlackRoute
{
    /**
     * Constructor
     *
     * @param string|null $channel Channel name
     * @param string|null $token OAuth token
     */
    public function __construct(
        public ?string $channel = null,
        public ?string $token = null,
    ) {
    }

    /**
     * Slack route builder
     *
     * @param string|null $channel Channel name
     * @param string|null $token OAuth token
     * @return static
     */
    public static function new(?string $channel = null, ?string $token = null): static
    {
        return new static($channel, $token); // @phpstan-ignore-line
    }
}
