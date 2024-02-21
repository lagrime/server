<?php

interface IEncryptionService
{
    public function generateKeyPair(int $bits = 2048): KeyPair;

    public function encryptWithPublicKey(string $data, string $publicKey): string;

    public function decryptWithPrivateKey(string $encryptedData, string $privateKey): string;
}