<?php
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

class ClassModel {

    public static function create($name, $grade_id) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO classes (name, grade_id) VALUES (?, ?)"
        );
        return $stmt->execute([$name, $grade_id]);
    }

    public static function all() {
        $db = Database::connect();
        return $db->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_OBJ);
    }

    // <-- Add this method
    public static function find($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM classes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
