<?php

interface IDatabaseAccessor
{
    function getUserByName(string $user_name): User;

    function getUserByNameOrNull(string $user_name): ?User;

    function insertMessage(Message $message): void;

    function getMessageById(int $message_id): Message;

    function getMessageByIdOrNull(int $message_id): ?Message;
}
