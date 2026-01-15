<?php
require_once __DIR__ . '/../config/Database.php';

use Config\Database;

class Term {
    public static function add($term_number, $school_year_id) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO terms (term_number, school_year_id) VALUES (?, ?)"
        );
        return $stmt->execute([$term_number, $school_year_id]);
    }

    public static function all() {
        $db = Database::connect();
        return $db->query("SELECT * FROM terms")->fetchAll(PDO::FETCH_OBJ);
    }
}
