<?php

class EncryptionService implements IEncryptionService
{

    public function generateKeyPair(int $bits = 2048): KeyPair
    {
        $config = array(
            "private_key_bits" => $bits,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        $keyPair = openssl_pkey_new($config);
        openssl_pkey_export($keyPair, $privateKey);

        $publicKeyDetails = openssl_pkey_get_details($keyPair);
        $publicKey = $publicKeyDetails["key"];

        return new KeyPair($privateKey, $publicKey);
    }

    public function encryptWithPublicKey(string $data, string $publicKey): string
    {
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return base64_encode($encrypted);
    }

    public function decryptWithPrivateKey(string $encryptedData, string $privateKey): string
    {
        $encrypted = base64_decode($encryptedData);
        openssl_private_decrypt($encrypted, $decrypted, $privateKey);
        return $decrypted;
    }
}