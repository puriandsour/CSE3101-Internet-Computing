<?php
// models/User.php
require_once 'models/Model.php';

class User extends Model
{

    // Find user by email
    public static function findByEmail($email)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Find user by ID
    public static function findById($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Save/Create a new user with Role
    // Expects array: [username, email, password_hash, first_name, last_name, is_active]
    public function save($userData, $roleId)
    {
        try {
            $this->db->beginTransaction();

            // 1. Insert User
            $sql = "INSERT INTO users (username, email, password_hash, first_name, last_name, is_active) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($userData);

            $userId = $this->db->lastInsertId();

            // 2. Assign Role
            $sqlRole = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
            $stmtRole = $this->db->prepare($sqlRole);
            $stmtRole->execute([$userId, $roleId]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            // Log error
            return false;
        }
    }
}
