<?php
require_once __DIR__ . '/../config/Database.php';

use Config\Database;

class Student {

    public static function create($first_name, $last_name, $class_id) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO students (first_name, last_name, class_id) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$first_name, $last_name, $class_id]);
    }

     public static function all() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM students ORDER BY first_name, last_name");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function find($id) {
    $db = Database::connect();
    $stmt = $db->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_OBJ);
}

}
