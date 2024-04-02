<?php

class AuthCompleteController extends JsonController
{
    public function index(): string
    {
        $kernel = KernelRepository::get();
        $request = $kernel->get(IRequest::class);
        $username = $request->fetchOrNull("user");

        if ($username == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "User parameter found",
            ], 400);
        }

        $decryptedChallenge = $request->fetchOrNull("decrypted_challenge");

        if ($decryptedChallenge == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Decrypted challenge parameter found",
            ], 400);
        }

        $sessionHandler = $kernel->get(ISessionHandler::class);
        $originalChallenge = $sessionHandler->getChallengeOrNull($username);

        if ($originalChallenge == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Could not find an open challenge for that user.",
            ], 400);
        }

        $decryptedMatchesOriginal = $decryptedChallenge == $originalChallenge;

        if (!$decryptedMatchesOriginal) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Your solution does not match the original challenge.",
            ], 400);
        }

        $token = substr(str_shuffle(md5(time())), 0, 32);
        $sessionHandler->setToken($username, $token);
        $sessionHandler->endChallenge($username);

        return $this->respond([
            "status" => "success",
            "access_token" => $token,
        ]);
    }
}