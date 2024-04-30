<?php

namespace Illuminate\Notifications\Messages;


class MrMessagingMessage
{
    public const RESPONSE_STATUS_DELIVERED = 'DELIVRD';
    public const RESPONSE_STATUS_EXPIRED = 'EXPIRED';
    public const RESPONSE_STATUS_DELETED = 'DELETED';
    public const RESPONSE_STATUS_UNDELIVERED = 'UNDELIV';
    public const RESPONSE_STATUS_ACCEPTED = 'ACCEPTD';
    public const RESPONSE_STATUS_UNKNOWN = 'UNKNOWN';
    public const RESPONSE_STATUS_REJECTED = 'REJECTD';
    public const RESPONSE_STATUSES = [
        self::RESPONSE_STATUS_DELIVERED,
        self::RESPONSE_STATUS_EXPIRED,
        self::RESPONSE_STATUS_DELETED,
        self::RESPONSE_STATUS_UNDELIVERED,
        self::RESPONSE_STATUS_ACCEPTED,
        self::RESPONSE_STATUS_UNKNOWN,
        self::RESPONSE_STATUS_REJECTED,
    ];

    private string $content;

    public function __construct(string $content = '')
    {
        $this->content($content);
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     * @return MrMessagingMessage
     */
    public function content(string $content) : self
    {
        $this->content = trim($content);

        return $this;
    }

    /**
     * Get the message content.
     *
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }
}
