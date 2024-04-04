<?php

interface IDatabaseAccessor
{
    function getUserByName(string $user_name): User;

    function getUserByNameOrNull(string $user_name): ?User;

    function setPublicKey(string $user_name, string $public_key): void;
}
