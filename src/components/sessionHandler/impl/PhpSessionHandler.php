<?php

class PhpSessionHandler implements ISessionHandler
{
    private int $sessionTimeout = 1800; // 30 minutes in seconds
    private int $challengeTimeout = 300; // 5 minutes in seconds

    public function startChallenge($username, $randomString): void
    {
        $_SESSION['challenge'][$username] = [
            'randomString' => $randomString,
            'startTime' => time()
        ];
    }

    public function getChallenge($username)
    {
        if (isset($_SESSION['challenge'][$username])) {
            return $_SESSION['challenge'][$username]['randomString'];
        }
        return null;
    }

    public function endChallenge($username): void
    {
        unset($_SESSION['challenge'][$username]);
    }

    public function setToken($username, $token): void
    {
        $_SESSION['token'][$username] = [
            'token' => $token,
            'lastUsed' => time()
        ];
    }

    public function tokenWasUsed($username): void
    {
        if (isset($_SESSION['token'][$username])) {
            $_SESSION['token'][$username]['lastUsed'] = time();
        }
    }

    /**
     * @throws Exception
     */
    public function getUsernameFromToken($token): string
    {
        foreach ($_SESSION['token'] as $username => $data) {
            if ($data['token'] === $token) {
                return $username;
            }
        }
        throw new Exception("Could not find this user.");
    }

    public function cleanupExpiredSessions(): void
    {
        $currentTime = time();

        foreach ($_SESSION['challenge'] as $username => $data) {
            if ($currentTime - $data['startTime'] > $this->challengeTimeout) {
                unset($_SESSION['challenge'][$username]);
            }
        }

        foreach ($_SESSION['token'] as $username => $data) {
            if ($currentTime - $data['lastUsed'] > $this->sessionTimeout) {
                unset($_SESSION['token'][$username]);
            }
        }
    }
}