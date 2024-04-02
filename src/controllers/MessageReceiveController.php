<?php

class MessageReceiveController extends JsonController
{

    public function index(): string
    {
        $kernel = KernelRepository::get();
        $request = $kernel->get(IRequest::class);

        $rawMessage = $request->fetchOrNull("message");

        if ($rawMessage == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Please specify a message",
            ], 400);
        }

        $senderUsername = $request->fetchOrNull("sender");

        if ($senderUsername == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Please specify a receiver",
            ], 400);
        }

        $receiverUsername = $request->fetchOrNull("receiver");

        if ($receiverUsername == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Please specify a receiver",
            ], 400);
        }

        $databaseAccessor = $kernel->get(IDatabaseAccessor::class);

        try {
            $databaseAccessor->getUserByName($receiverUsername);
        } catch (Exception $exception) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Could not find this user.",
            ], 400);
        }

        $newMessage = new Message(0,
            $rawMessage,
            $receiverUsername . "@" . HOSTNAME,
            $senderUsername,
            date('Y-m-d H:i:s')
        );

        $databaseAccessor->insertMessage($newMessage);

        return $this->respond([
            "status" => "success",
        ]);
    }

}