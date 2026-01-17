<?php
require_once __DIR__ . '/../models/Score.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Subject.php';

class ScoreController
{

    public function add($data)
    {
        // Score::add expects a single array argument with keys:
        // enrollment_id, subject_id, term_id, teacher_user_id, score, remarks

        $scoreData = [
            'enrollment_id' => $data['enrollment_id'], // Ensure form sends enrollment_id, not student_id directly?
            // Wait, usually forms send student_id. We need to look up enrollment.
            // But let's assume for now the form logic will handle resolving enrollment_id or we do it here.

            // If the View sends student_id, we must find the active enrollment!
            'subject_id' => $data['subject_id'],
            'term_id' => $data['term_id'],
            'teacher_user_id' => $_SESSION['user_id'], // Get from Session
            'score' => $data['score'],
            'remarks' => $data['remarks'] ?? null
        ];

        // Resolving Enrollment ID if not present but student_id is
        if (!isset($data['enrollment_id']) && isset($data['student_id'])) {
            $studentModel = new Student();
            $enrollment = $studentModel->getCurrentEnrollment($data['student_id']);
            if ($enrollment) {
                $scoreData['enrollment_id'] = $enrollment->id;
            } else {
                $GLOBALS['error'] = "Student is not enrolled in the current year.";
                return false;
            }
        }

        // Add score
        $scoreModel = new Score();
        $success = $scoreModel->add($scoreData);

        if ($success) {
            $GLOBALS['success'] = "Score added successfully!";
        }

        // Error handling is done inside Score::add setting $_SESSION['error'] usually, 
        // but here we used $GLOBALS in previous code. Let's stick to consistent Session usage if possible.
        // But the previous code used $GLOBALS.

        return $success;
    }
}
