<?php

class Message
{
    private int $messageId;
    private string $messageRaw;
    private string $messageReceiver;
    private int $messageSender;
    private string $messageCreatedAt;

    public function __construct(
        int    $messageId,
        string $messageRaw,
        string $messageReceiver,
        int    $messageSender,
        string $messageCreatedAt
    )
    {
        $this->messageId = $messageId;
        $this->messageRaw = $messageRaw;
        $this->messageReceiver = $messageReceiver;
        $this->messageSender = $messageSender;
        $this->messageCreatedAt = $messageCreatedAt;
    }

    public function getMessageId(): int
    {
        return $this->messageId;
    }

    public function getMessageRaw(): string
    {
        return $this->messageRaw;
    }

    public function getMessageReceiver(): string
    {
        return $this->messageReceiver;
    }

    public function getMessageSender(): int
    {
        return $this->messageSender;
    }

    public function getMessageCreatedAt(): string
    {
        return $this->messageCreatedAt;
    }
}
