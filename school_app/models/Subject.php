<?php

require_once __DIR__ . '/../config/Database.php';

use Config\Database;

class Subject {

    public static function create($name, $grade_id) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO subjects (name, grade_id) VALUES (?, ?)"
        );
        return $stmt->execute([$name, $grade_id]);
    }

    public static function byGrade($grade_id) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "SELECT * FROM subjects WHERE grade_id = ?"
        );
        $stmt->execute([$grade_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
