<?php

class RegisterController extends JsonController
{

    public function index(): string
    {
        $kernel = KernelRepository::get();
        $request = $kernel->get(IRequest::class);

        $publicKey = $request->fetchOrNull('public_key');

        if (!$publicKey) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Please specify a public key",
            ], 400);
        }

        $userName = $request->fetchOrNull('user_name');

        if (!$userName) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Please specify a user name",
            ], 400);
        }4

        $databaseAccessor = $kernel->get(IDatabaseAccessor::class);
        $databaseAccessor->createUser($userName, $publicKey);

        return $this->respond([
            "status" => "success",
        ]);
    }

}