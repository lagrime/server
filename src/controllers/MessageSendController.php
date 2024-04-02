<?php

class MessageSendController extends JsonController
{

    public function index(): string
    {
        $kernel = KernelRepository::get();
        $request = $kernel->get(IRequest::class);
        $receiverUsername = $request->fetchOrNull("receiver");

        if ($receiverUsername == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Please specify a receiver",
            ], 400);
        }

        $rawMessage = $request->fetchOrNull("message");

        if ($rawMessage == null) {
            return $this->respond([
                "status" => "error",
                "error_message" => "Please specify a message",
            ], 400);
        }

        $sessionHandler = $kernel->get(ISessionHandler::class);
        $providedToken = $request->fetchOrNull('token');
        $senderUsername = $sessionHandler->getUsernameFromToken($providedToken);
        $receiverDomain = str_split($receiverUsername, "@");

        if ($receiverDomain == HOSTNAME) {
            $databaseAccessor = $kernel->get(IDatabaseAccessor::class);

            $newMessage = new Message(0,
                $rawMessage,
                $receiverUsername,
                $senderUsername,
                date('Y-m-d H:i:s')
            );

            $databaseAccessor->insertMessage($newMessage);
        } else {
            // Send to remote
        }

        return $this->respond([
            "status" => "success",
        ]);
    }

}