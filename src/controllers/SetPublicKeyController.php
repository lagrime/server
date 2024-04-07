<?php

class SetPublicKeyController extends JsonController
{

    public function index(): string
    {
        $kernel = KernelRepository::get();
        $request = $kernel->get(IRequest::class);

        $newKey = $request->fetchOrNull('new_key');

        if (!$newKey) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Please specify a new key",
            ], 400);
        }

        $token = $request->fetchOrNull('token');
        $sessionHandler = $kernel->get(ISessionHandler::class);
        $userName = $sessionHandler->getUsernameFromToken($token);

        $databaseAccessor = $kernel->get(IDatabaseAccessor::class);
        $databaseAccessor->setPublicKey($userName, $newKey);

        return $this->respond([
            "status" => "success",
        ]);
    }

}