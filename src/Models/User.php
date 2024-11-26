<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class User extends BaseModel
{
    /**
     * Save a new user to the database.
     *
     * @param array $data
     * @return string The ID of the inserted user.
     */
    public function save($data)
    {
        $sql = "INSERT INTO users 
                (first_name, last_name, email, password)
                VALUES 
                (:first_name, :last_name, :email, :password_hash)";
        
        $statement = $this->db->prepare($sql);
        $password_hash = $this->hashPassword($data['password']);
    
        try {
            $statement->execute([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password_hash' => $password_hash
            ]);
        } catch (\PDOException $e) {
            // Handle the exception (log it or show a user-friendly message)
            throw new \Exception("Error saving user: " . $e->getMessage());
        }

        return $this->db->lastInsertId();
    }

    /**
     * Get a user's ID and first name by email.
     *
     * @param string $email
     * @return array|null The user's data, or null if not found.
     */
    public function getUserID($email)
    {
        $sql = "SELECT id, first_name FROM users WHERE email = :email LIMIT 1";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute(['email' => $email]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Handle error (e.g., log or display a message)
            throw new \Exception("Error retrieving user: " . $e->getMessage());
        }

        return $user ? $user : null;
    }

    /**
     * Hash a password using a secure algorithm.
     *
     * @param string $password
     * @return string The hashed password.
     */
    protected function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify if the provided password matches the stored password.
     *
     * @param string $email
     * @param string $password
     * @return bool True if the password matches, otherwise false.
     */
    public function verifyAccess($email, $password)
    {
        $sql = "SELECT password FROM users WHERE email = :email LIMIT 1";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute(['email' => $email]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error verifying password: " . $e->getMessage());
        }

        if (!$result) {
            return false;
        }

        return password_verify($password, $result['password']);
    }

    /**
     * Get all users from the database.
     *
     * @return array List of all users.
     */
    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error retrieving users: " . $e->getMessage());
        }
    }

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return array|null The user's data, or null if not found.
     */
    public function getUserByID($id)
    {
        $sql = "SELECT id, first_name, last_name, email FROM users WHERE id = :id LIMIT 1";
        $statement = $this->db->prepare($sql);

        try {
            $statement->execute(['id' => $id]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error retrieving user by ID: " . $e->getMessage());
        }

        return $user ? $user : null;
    }
}
