<?php
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

class Report {

    public static function studentReport($studentId, $termId) {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT 
                students.first_name,
                students.last_name,
                classes.name AS class_name,
                terms.term_number,
                subjects.name AS subject_name,
                scores.score
            FROM scores
            JOIN students ON scores.student_id = students.id
            JOIN classes ON students.class_id = classes.id
            JOIN subjects ON scores.subject_id = subjects.id
            JOIN terms ON scores.term_id = terms.id
            WHERE students.id = ? AND terms.id = ?
        ");
        $stmt->execute([$studentId, $termId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function studentTotals($studentId, $termId) {
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT 
                SUM(score) AS total_score,
                AVG(score) AS average_score,
                COUNT(score) AS subjects_count
            FROM scores
            WHERE student_id = ? AND term_id = ?
        ");
        $stmt->execute([$studentId, $termId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
