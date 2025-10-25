<?php
declare(strict_types=1);

namespace Cake\SlackNotification\BlockKit;

/**
 * Event Metadata
 *
 * Defines metadata for Slack events.
 */
class EventMetadata
{
    /**
     * Event type
     *
     * @var string
     */
    protected string $type;

    /**
     * Event payload
     *
     * @var array<string, mixed>
     */
    protected array $payload;

    /**
     * Constructor
     *
     * @param string $type Event type
     * @param array<string, mixed> $payload Event payload
     */
    public function __construct(string $type, array $payload = [])
    {
        $this->type = $type;
        $this->payload = $payload;
    }

    /**
     * Event metadata builder
     *
     * @param string $type Event type
     * @param array<string, mixed> $payload Event payload
     * @return static
     */
    public static function new(string $type, array $payload = []): static
    {
        return new static($type, $payload); // @phpstan-ignore-line
    }

    /**
     * Convert to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'event_type' => $this->type,
            'event_payload' => $this->payload,
        ];
    }
}
