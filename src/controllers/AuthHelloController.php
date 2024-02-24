<?php

class HelloController extends JsonController
{

    public function world(): string
    {
        $kernel = KernelRepository::get();
        $request = $kernel->get(IRequest::class);
        $username = $request->fetchOrNull("user");

        if ($username == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "User not found",
            ]);
        }

        $databaseFetcher = $kernel->get(IDatabaseFetcher::class);
        $user = $databaseFetcher->getUserByNameOrNull($username);

        if ($user == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "User not found",
            ]);
        }

        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16);

        try {
            $publicKey = $user->getPublicKey();
            $successfullyEncrypted = openssl_public_encrypt($randomString, $encrypted, $publicKey);

            if (!$successfullyEncrypted) {
                return $this->respond([
                    "status" => "error",
                    "error_message" => "Error while encrypting the challenge secret.",
                ]);
            }

            $encrypted = base64_encode($encrypted);

            $sessionHandler = $kernel->get(ISessionHandler::class);
            $sessionHandler->startChallenge($user->getName(), $randomString);

            return $this->respond([
                "encrypted_challenge" => $encrypted,
            ]);
        } catch (SodiumException $e) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Error while encrypting the challenge secret.",
            ]);
        }
    }
}