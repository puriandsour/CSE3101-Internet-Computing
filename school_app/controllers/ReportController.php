<?php
require_once __DIR__ . '/../config/Database.php';

use Config\Database;

class ReportController {

    public function studentReport($student_id, $term_id) {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT 
                subjects.name AS subject,
                scores.score
            FROM scores
            JOIN subjects ON subjects.id = scores.subject_id
            WHERE scores.student_id = ?
            AND scores.term_id = ?
        ");

        $stmt->execute([$student_id, $term_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
