<?php

namespace Kara;

class Message
{
    private \RdKafka\Message $rdMessage;

    public function __construct(\RdKafka\Message $message)
    {
        $this->rdMessage = $message;
    }

    public function json()
    {
        return json_decode($this->rdMessage->payload);
    }

    public function unserialize()
    {
        return unserialize($this->rdMessage->payload);
    }

    public function __get($name)
    {
        return $this->rdMessage->$name ?? null;
    }

    public function __set($name, $value)
    {
        $this->rdMessage->$name = $value;
    }

    public function getError(): ?string
    {
        return $this->rdMessage->err ? $this->rdMessage->errstr() : null;
    }
}
