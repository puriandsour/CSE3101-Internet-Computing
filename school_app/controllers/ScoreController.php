<?php
require_once __DIR__ . '/../models/Score.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Subject.php';

class ScoreController {

    public function add($data) {
        $db = \Config\Database::connect();

        // Check student
        $stmt = $db->prepare("SELECT * FROM students WHERE id=?");
        $stmt->execute([$data['student_id']]);
        if (!$stmt->fetch(PDO::FETCH_OBJ)) {
            $GLOBALS['error'] = "Error: Student does not exist.";
            return false;
        }

        // Check subject
        $stmt = $db->prepare("SELECT * FROM subjects WHERE id=?");
        $stmt->execute([$data['subject_id']]);
        if (!$stmt->fetch(PDO::FETCH_OBJ)) {
            $GLOBALS['error'] = "Error: Subject does not exist.";
            return false;
        }

        // Check term
        $stmt = $db->prepare("SELECT * FROM terms WHERE id=?");
        $stmt->execute([$data['term_id']]);
        if (!$stmt->fetch(PDO::FETCH_OBJ)) {
            $GLOBALS['error'] = "Error: Term does not exist.";
            return false;
        }

        // Add score
        $success = Score::add(
            $data['student_id'],
            $data['subject_id'],
            $data['term_id'],
            $data['score']
        );

        if ($success) {
            $GLOBALS['success'] = "Score added successfully!";
        } else {
            $GLOBALS['error'] = "Error adding score.";
        }

        return $success;
    }
}
