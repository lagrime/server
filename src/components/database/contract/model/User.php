<?php

class User
{
    private string $user_name;
    private string $user_public_key;

    public function __construct($user_name, $user_public_key)
    {
        $this->user_name = $user_name;
        $this->user_public_key = $user_public_key;
    }

    public function getName(): string
    {
        return $this->user_name;
    }

    public function getPublicKey(): string
    {
        return $this->user_public_key;
    }
}
