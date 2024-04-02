<?php

class AuthMiddleware extends JsonController implements IMiddleware
{

    public function canPass(): bool
    {
        $kernel = KernelRepository::get();

        $sessionHandler = $kernel->get(ISessionHandler::class);
        $request = $kernel->get(IRequest::class);
        $providedToken = $request->fetchOrNull('token');

        if ($providedToken == null) return false;

        try {
            $usernameToToken = $sessionHandler->getUsernameFromToken($providedToken);
            $isValidSession = $sessionHandler->isTokenValid($usernameToToken);

            if ($isValidSession) {
                $sessionHandler->tokenWasUsed($usernameToToken);
                return true;
            }

            $sessionHandler->destroyToken($usernameToToken);
        } catch (Exception $exception) {
            return false;
        }

        return false;
    }

    function onFail(): string
    {
        return $this->respond([
            "status" => "error",
            "error_message" => "You are not authenticated",
        ], 400);
    }
}