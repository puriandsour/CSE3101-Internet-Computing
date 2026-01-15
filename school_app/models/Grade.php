<?php
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

class Grade {
    public static function all() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM grades");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function create($name) {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO grades (name) VALUES (?)");
        return $stmt->execute([$name]);
    }
}
