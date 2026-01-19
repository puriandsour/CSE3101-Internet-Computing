<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/ClassModel.php';

class ReportController
{

    public function index()
    {
        // Get recent reports - FIXED SQL with student_id
        $db = Database::connect();
        $stmt = $db->query("
            SELECT 
                s.id as student_id,
                s.first_name,
                s.last_name,
                s.admission_no,
                c.name as class_name,
                sy.name as term_name,
                NOW() as generated_at
            FROM students s
            JOIN enrollments e ON s.id = e.student_id
            JOIN classes c ON e.class_id = c.id
            JOIN school_years sy ON e.school_year_id = sy.id
            WHERE s.is_active = 1
            LIMIT 10
        ");
        $reports = $stmt->fetchAll(PDO::FETCH_OBJ);

        return ['reports' => $reports];
    }

    public function generate()
    {
        $classes = ClassModel::getAll();
        $terms = Term::getAll();

        return [
            'classes' => $classes,
            'terms' => $terms
        ];
    }

    public function create($data)
    {
        if (empty($data['student_id']) || empty($data['term_id'])) {
            $_SESSION['error'] = "Please select student and term.";
            header("Location: index.php?controller=report&action=generate");
            exit;
        }

        $student = Student::find($data['student_id']);

        if ($student) {
            $_SESSION['success'] = "Report generated for " . $student->first_name . " " . $student->last_name . "!";
            // Redirect to view the report
            header("Location: index.php?controller=report&action=view&student_id=" . $data['student_id'] . "&term_id=" . $data['term_id']);
        } else {
            $_SESSION['error'] = "Student not found.";
            header("Location: index.php?controller=report&action=generate");
        }
        exit;
    }
}
