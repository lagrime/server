<?php

class DatabaseAccessor implements IDatabaseAccessor
{
    private PDO $db;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        try {
            $this->db = new PDO("mysql:host=" . DB_HOST . ":" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getUserByName($user_name): User
    {
        $query = "SELECT * FROM user WHERE user_name = :user_name";
        $stmt = $this->db->prepare($query);
        $user_name = htmlspecialchars(strip_tags($user_name));
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("Could not find this user.");
        }

        return new User($result['user_name'], $result['user_public_key']);
    }

    /**
     * @throws Exception
     */
    public function getUserByNameOrNull(string $user_name): ?User
    {
        try {
            return $this->getUserByName($user_name);
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function insertMessage(Message $message): void
    {
        $query = "INSERT INTO your_message_table (message_raw, message_receiver, message_sender, message_created_at)
                  VALUES (:message_raw, :message_receiver, :message_sender, :message_created_at)";

        $statement = $this->db->prepare($query);
        $statement->execute([
            'message_raw' => $message->getMessageRaw(),
            'message_receiver' => $message->getMessageReceiver(),
            'message_sender' => $message->getMessageSender(),
            'message_created_at' => $message->getMessageCreatedAt(),
        ]);
    }


    /**
     * @throws Exception
     */
    function getMessagesByReceiver(string $receiver): array
    {
        $query = "SELECT * FROM message WHERE message_receiver = :user_name";
        $stmt = $this->db->prepare($query);
        $user_name = htmlspecialchars(strip_tags($receiver));
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();

        $messages = [];
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = new Message(
                $result['message_id'],
                $result['message_raw'],
                $result['message_receiver'],
                $result['message_sender'],
                $result['message_created_at']
            );
        }

        return $messages;
    }

    /**
     * @throws Exception
     */
    function getMessagesBySender(string $sender): array
    {
        $query = "SELECT * FROM message WHERE message_sender = :user_name";
        $stmt = $this->db->prepare($query);
        $user_name = htmlspecialchars(strip_tags($sender));
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();

        $messages = [];
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = new Message(
                $result['message_id'],
                $result['message_raw'],
                $result['message_receiver'],
                $result['message_sender'],
                $result['message_created_at']
            );
        }

        return $messages;
    }
}