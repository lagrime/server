<?php

interface IDatabaseFetcher
{
    function getUserByName(string $user_name): User;

    function getUserByNameOrNull(string $user_name): ?User;
}