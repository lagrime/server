<?php

interface ISessionHandler
{
    function startChallenge($username, $randomString): void;

    function getChallenge($username): string;

    function getChallengeOrNull($username): ?string;

    function endChallenge($username): void;

    function setToken($username, $token): void;

    function tokenWasUsed($username): void;

    function getUsernameFromToken($token): string;

    function isTokenValid($username): bool;
}