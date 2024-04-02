<?php

class AuthHelloController extends JsonController
{
    public function index(): string
    {
        $kernel = KernelRepository::get();
        $request = $kernel->get(IRequest::class);
        $username = $request->fetchOrNull("user");

        if ($username == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "User parameter missing",
            ], 400);
        }

        $databaseAccessor = $kernel->get(IDatabaseAccessor::class);
        $user = $databaseAccessor->getUserByNameOrNull($username);

        if ($user == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "User not found",
            ], 400);
        }

        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16);

        try {
            $publicKey = $user->getPublicKey();
            $successfullyEncrypted = openssl_public_encrypt($randomString, $encrypted, $publicKey);

            if (!$successfullyEncrypted) {
                return $this->respond([
                    "status" => "error",
                    "error_message" => "Error while encrypting the challenge secret.",
                ], 500);
            }

            $encrypted = base64_encode($encrypted);
            $sessionHandler = $kernel->get(ISessionHandler::class);
            $sessionHandler->startChallenge($user->getName(), $randomString);

            return $this->respond([
                "status" => "success",
                "encrypted_challenge" => $encrypted,
            ]);
        } catch (Exception $exception) {
            echo $exception->getMessage();

            return $this->respond([
                "status" => "error",
                "error_message" => "Error while managing your session.",
            ], 500);
        }
    }
}