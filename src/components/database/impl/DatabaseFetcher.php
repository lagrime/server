<?php

class DatabaseFetcher implements IDatabaseFetcher
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

    public function getUserByNameOrNull(string $user_name): ?User
    {
        try {
            return $this->getUserByName($user_name);
        } catch (Exception $exception) {
            return null;
        }
    }

}