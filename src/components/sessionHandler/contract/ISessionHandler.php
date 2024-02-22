<?php

interface ISessionHandler
{
    public function startChallenge($username, $randomString);

    public function getChallenge($username);

    public function endChallenge($username);

    public function setToken($username, $token);

    public function tokenWasUsed($username);

    public function getUsernameFromToken($token);

    public function cleanupExpiredSessions();
}