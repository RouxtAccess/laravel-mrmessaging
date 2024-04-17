<?php

namespace Illuminate\Notifications\Messages;


class MrMessagingMessage
{
    public const LINE_BREAK = '|';

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
        $this->content = trim(str_replace('<br>', self::LINE_BREAK, nl2br($content, false)));

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
