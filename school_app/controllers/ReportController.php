<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/ClassModel.php';

class ReportController
{

    /**
     * Report Landing Page
     */
    public function index()
    {
        return [];
    }

    /**
     * Individual Student Report Selection
     */
    public function student()
    {
        $classes = ClassModel::getAll(['is_active' => 1]);
        $terms = Term::getAll();

        return [
            'classes' => $classes,
            'terms' => $terms
        ];
    }

    /**
     * Performance Analytics View
     */
    public function performance()
    {
        $db = Database::connect();
        $classId = $_GET['class_id'] ?? null;
        $termId = $_GET['term_id'] ?? null;

        $classes = ClassModel::getAll(['is_active' => 1]);
        $terms = Term::getAll();

        $subjects = [];
        $overallAverage = 0;

        if ($classId && $termId) {
            $class = ClassModel::find($classId);
            if ($class) {
                // Get subjects for this grade with averages for the ENTIRE GRADE
                $stmt = $db->prepare("
                    SELECT 
                        s.id, s.name,
                        COALESCE(sub_avg.avg_score, 0) as avg_score
                    FROM subjects s
                    LEFT JOIN (
                        SELECT sc.subject_id, AVG(sc.score) as avg_score
                        FROM scores sc
                        JOIN enrollments e ON sc.enrollment_id = e.id
                        JOIN classes c ON e.class_id = c.id
                        WHERE sc.term_id = ? AND c.grade_id = ?
                        GROUP BY sc.subject_id
                    ) sub_avg ON s.id = sub_avg.subject_id
                    WHERE s.grade_id = ? AND s.is_active = 1
                ");
                $stmt->execute([$termId, $class->grade_id, $class->grade_id]);
                $subjects = $stmt->fetchAll(PDO::FETCH_OBJ);

                // Overall average for the ENTIRE GRADE
                $scoresStmt = $db->prepare("
                    SELECT AVG(sc.score) as avg
                    FROM scores sc
                    JOIN enrollments e ON sc.enrollment_id = e.id
                    JOIN classes c ON e.class_id = c.id
                    WHERE c.grade_id = ? AND sc.term_id = ?
                ");
                $scoresStmt->execute([$class->grade_id, $termId]);
                $overallAverage = round($scoresStmt->fetch(PDO::FETCH_OBJ)->avg ?? 0, 1);
            }
        }

        return [
            'classes' => $classes,
            'terms' => $terms,
            'selectedClassId' => $classId,
            'selectedTermId' => $termId,
            'subjects' => $subjects,
            'overallAverage' => $overallAverage
        ];
    }

    /**
     * View/Generate Individual Student Report
     */
    public function view()
    {
        $studentId = $_GET['student_id'] ?? null;
        $termId = $_GET['term_id'] ?? null;

        if (!$studentId || !$termId) {
            $_SESSION['error'] = "Student and Term are required.";
            header("Location: index.php?controller=report&action=student");
            exit;
        }

        $student = Student::find($studentId);
        $term = Term::find($termId);

        $db = Database::connect();

        // Get enrollment for this year
        $stmt = $db->prepare("
            SELECT e.*, c.name as class_name, g.name as grade_name, sy.name as year_name
            FROM enrollments e
            JOIN classes c ON e.class_id = c.id
            JOIN grades g ON c.grade_id = g.id
            JOIN school_years sy ON e.school_year_id = sy.id
            WHERE e.student_id = ? 
              AND e.school_year_id = (SELECT school_year_id FROM terms WHERE id = ?)
              AND e.status = 'ACTIVE'
            LIMIT 1
        ");
        $stmt->execute([$studentId, $termId]);
        $enrollment = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$enrollment) {
            $_SESSION['error'] = "Student has no enrollment for this term's school year.";
            header("Location: index.php?controller=report&action=student");
            exit;
        }

        // Get scores
        $stmt = $db->prepare("
            SELECT s.*, sub.name as subject_name
            FROM scores s
            JOIN subjects sub ON s.subject_id = sub.id
            WHERE s.enrollment_id = ? AND s.term_id = ?
        ");
        $stmt->execute([$enrollment->id, $termId]);
        $scores = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Calculate Average
        $total = 0;
        foreach ($scores as $s)
            $total += $s->score;
        $average = count($scores) > 0 ? round($total / count($scores), 1) : 0;

        return [
            'student' => $student,
            'term' => $term,
            'enrollment' => $enrollment,
            'scores' => $scores,
            'average' => $average
        ];
    }
}
