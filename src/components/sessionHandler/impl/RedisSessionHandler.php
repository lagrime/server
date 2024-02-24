<?php

class RedisSessionHandler implements ISessionHandler
{
    private int $sessionTimeout = 1800; // 30 minutes in seconds
    private int $challengeTimeout = 300; // 5 minutes in seconds
    private Redis $redis;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(gethostbyname(REDIS_HOST), REDIS_PORT);
        $this->redis->auth(REDIS_PASSWORD);
        $this->redis->select(REDIS_DATABASE);
    }

    /**
     * @throws Exception
     */
    public function startChallenge($username, $randomString): void
    {
        $key = $this->getChallengeKey($username);
        $data = [
            'randomString' => $randomString,
            'startTime' => time()
        ];
        $this->redis->set($key, json_encode($data));
    }

    /**
     * @throws Exception
     */
    public function getChallenge($username): string
    {
        $key = $this->getChallengeKey($username);
        $data = $this->redis->get($key);

        if ($data !== false) {
            $data = json_decode($data, true);
            return $data['randomString'];
        }

        throw new Exception("Could not find a challenge for that user.");
    }

    public function getChallengeOrNull($username): ?string
    {
        try {
            return $this->getChallenge($username);
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function endChallenge($username): void
    {
        $key = $this->getChallengeKey($username);
        $this->redis->del($key);
    }

    /**
     * @throws Exception
     */
    public function setToken($username, $token): void
    {
        $key = $this->getTokenKey($username);
        $data = [
            'token' => $token,
            'lastUsed' => time()
        ];
        $this->redis->set($key, json_encode($data));
    }

    /**
     * @throws Exception
     */
    public function tokenWasUsed($username): void
    {
        $key = $this->getTokenKey($username);
        $data = $this->redis->get($key);

        if ($data !== false) {
            $data = json_decode($data, true);
            $data['lastUsed'] = time();
            $this->redis->set($key, json_encode($data));
        }
    }

    /**
     * @throws Exception
     */
    public function getUsernameFromToken($token): string
    {
        foreach ($this->redis->keys('token:*') as $key) {
            $data = json_decode($this->redis->get($key), true);
            if ($data['token'] === $token) {
                return str_replace('token:', '', $key);
            }
        }

        throw new Exception("Could not find this user.");
    }

    /**
     * @throws Exception
     */
    public function cleanupExpiredSessions(): void
    {
        $currentTime = time();

        foreach ($this->redis->keys('challenge:*') as $key) {
            $data = json_decode($this->redis->get($key), true);
            if ($currentTime - $data['startTime'] > $this->challengeTimeout) {
                $this->redis->del($key);
            }
        }

        foreach ($this->redis->keys('token:*') as $key) {
            $data = json_decode($this->redis->get($key), true);
            if ($currentTime - $data['lastUsed'] > $this->sessionTimeout) {
                $this->redis->del($key);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function isTokenValid($username): bool
    {
        $key = $this->getTokenKey($username);
        $data = $this->redis->get($key);

        if ($data !== false) {
            $data = json_decode($data, true);
            $currentTime = time();
            $lastUsedTime = $data['lastUsed'];
            return ($currentTime - $lastUsedTime) <= $this->sessionTimeout;
        }

        return false;
    }

    private function getChallengeKey($username): string
    {
        return "challenge:$username";
    }

    private function getTokenKey($username): string
    {
        return "token:$username";
    }
}