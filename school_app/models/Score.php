<?php
require_once __DIR__ . '/../config/Database.php';
use Config\Database;


class Score {
    public static function add($student,$subject,$term,$score) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO scores (student_id,subject_id,term_id,score)
             VALUES (?,?,?,?)"
        );
        return $stmt->execute([$student,$subject,$term,$score]);
    }
}
