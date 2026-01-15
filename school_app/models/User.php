<?php
require_once __DIR__ . '/../config/Database.php';
use Config\Database;


class User {
    public static function findByEmail($email) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($data) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO users (name,email,role,password_hash)
             VALUES (?,?,?,?)"
        );
        return $stmt->execute($data);
    }
}
