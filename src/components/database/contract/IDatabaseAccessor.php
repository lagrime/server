<?php

interface IDatabaseAccessor
{
    function getUserByName(string $user_name): User;

    function getUserByNameOrNull(string $user_name): ?User;

    function insertMessage(Message $message): void;

    function getMessagesByReceiver(string $receiver): array;

    function getMessagesBySender(string $sender): array;
}
