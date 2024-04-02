<?php

class GetPublicKeyController extends JsonController
{

    public function index(): string
    {
        $kernel = KernelRepository::get();
        $request = $kernel->get(IRequest::class);
        $keyUsername = $request->fetchOrNull("key-user");

        if ($keyUsername == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Please specify a key-user",
            ], 400);
        }

        $databaseAccessor = $kernel->get(IDatabaseAccessor::class);
        $keyUser = $databaseAccessor->getUserByNameOrNull($keyUsername);

        if ($keyUser == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Key-user not found",
            ], 400);
        }

        return $this->respond([
            "status" => "success",
            "public_key" => $keyUser->getPublicKey(),
        ]);
    }

}