<?php
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

class SchoolYear {
    public static function create($name) {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO school_years (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public static function all() {
        $db = Database::connect();
        return $db->query("SELECT * FROM school_years")->fetchAll(PDO::FETCH_OBJ);
    }
}
