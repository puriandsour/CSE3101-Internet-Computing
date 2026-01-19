<?php
require_once __DIR__ . '/../models/Score.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Enrollment.php';

class ScoreController
{

    public function enter()
    {
        $db = Database::connect();

        // 1. Get current school year
        $yearStmt = $db->query("SELECT id FROM school_years WHERE is_current = 1 LIMIT 1");
        $currentYear = $yearStmt->fetch(PDO::FETCH_OBJ);
        $schoolYearId = $currentYear ? $currentYear->id : 0;

        // 2. Get classes that have active enrollments THIS year (Matching Dashboard Logic)
        $stmt = $db->prepare("
            SELECT DISTINCT c.*, g.name as grade_name, g.grade_number
            FROM classes c
            JOIN grades g ON c.grade_id = g.id
            JOIN enrollments e ON c.id = e.class_id
            WHERE c.is_active = 1 AND e.school_year_id = ? AND e.status = 'ACTIVE'
            ORDER BY g.grade_number, c.name
        ");
        $stmt->execute([$schoolYearId]);
        $classes = $stmt->fetchAll(PDO::FETCH_OBJ);

        // 3. Get terms for THIS year only
        $terms = Term::getBySchoolYear($schoolYearId);

        $selectedClassId = $_GET['class_id'] ?? null;
        $selectedTermId = $_GET['term_id'] ?? null;
        $selectedSubjectId = $_GET['subject_id'] ?? null;

        // If a class is selected, filter subjects for that grade
        $subjects = [];
        if ($selectedClassId) {
            $class = ClassModel::find($selectedClassId);
            if ($class) {
                $subjects = Subject::getByGrade($class->grade_id);
            }
        } elseif (count($classes) > 0) {
            // Default to subjects of first class if possible
            $subjects = Subject::getByGrade($classes[0]->grade_id);
        }

        return [
            'classes' => $classes,
            'terms' => $terms,
            'subjects' => $subjects,
            'selectedClassId' => $selectedClassId,
            'selectedTermId' => $selectedTermId,
            'selectedSubjectId' => $selectedSubjectId
        ];
    }

    public function manage()
    {
        // Just render the manage view - data loaded in view
    }

    public function getStudents()
    {
        header('Content-Type: application/json');

        $classId = $_GET['class_id'] ?? null;
        $termId = $_GET['term_id'] ?? null;
        $subjectId = $_GET['subject_id'] ?? null;

        if (!$classId) {
            echo json_encode(['error' => 'Class ID missing']);
            exit;
        }

        $db = Database::connect();

        // Standardize: Use Student::getByClass exactly like TeacherController::viewClass
        // This defaults to the current school year (is_current=1), ensuring we match the dashboard.
        $students = Student::getByClass($classId);

        // Optional: Attach scores for pre-population if term/subject provided
        if ($termId && $subjectId) {
            foreach ($students as $s) {
                $scoreStmt = $db->prepare("
                SELECT score as current_score, remarks
                FROM scores
                WHERE enrollment_id = ? AND subject_id = ? AND term_id = ?
                LIMIT 1
            ");
                $scoreStmt->execute([$s->enrollment_id, $subjectId, $termId]);
                $scoreData = $scoreStmt->fetch(PDO::FETCH_OBJ);

                $s->current_score = $scoreData ? $scoreData->current_score : '';
                $s->remarks = $scoreData ? $scoreData->remarks : '';
            }
        }

        echo json_encode(['students' => $students]);
        exit;
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=score&action=enter");
            exit;
        }

        $classId = $_POST['class_id'] ?? null;
        $termId = $_POST['term_id'] ?? null;
        $subjectId = $_POST['subject_id'] ?? null;
        $scores = $_POST['scores'] ?? [];

        if (!$classId || !$termId || !$subjectId || empty($scores)) {
            $_SESSION['error'] = "Please fill all required fields.";
            header("Location: index.php?controller=score&action=enter");
            exit;
        }

        $teacherId = $_SESSION['user_id'];
        $db = Database::connect();
        $saved = 0;

        foreach ($scores as $scoreData) {
            if (empty($scoreData['score']) || $scoreData['score'] < 0 || $scoreData['score'] > 100) {
                continue;
            }

            $checkStmt = $db->prepare("
                SELECT id FROM scores 
                WHERE enrollment_id = ? AND subject_id = ? AND term_id = ?
            ");
            $checkStmt->execute([
                $scoreData['enrollment_id'],
                $subjectId,
                $termId
            ]);

            if ($existing = $checkStmt->fetch(PDO::FETCH_OBJ)) {
                $updateStmt = $db->prepare("
                    UPDATE scores 
                    SET score = ?, remarks = ?, teacher_user_id = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                $updateStmt->execute([
                    $scoreData['score'],
                    $scoreData['remarks'] ?? null,
                    $teacherId,
                    $existing->id
                ]);
            } else {
                $insertStmt = $db->prepare("
                    INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                ");
                $insertStmt->execute([
                    $scoreData['enrollment_id'],
                    $subjectId,
                    $termId,
                    $teacherId,
                    $scoreData['score'],
                    $scoreData['remarks'] ?? null
                ]);
            }

            $saved++;
        }

        if ($saved > 0) {
            $_SESSION['success'] = "$saved score(s) saved successfully!";
        } else {
            $_SESSION['error'] = "No valid scores to save.";
        }

        header("Location: index.php?controller=score&action=enter");
        exit;
    }

    public function getSubjects()
    {
        header('Content-Type: application/json');
        $classId = $_GET['class_id'] ?? null;
        if (!$classId) {
            echo json_encode(['error' => 'Class ID required']);
            exit;
        }

        $subjects = Subject::getByClass($classId);
        echo json_encode(['subjects' => $subjects]);
        exit;
    }

    public function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM scores WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['success'] = "Score deleted successfully!";
        header("Location: index.php?controller=score&action=manage");
        exit;
    }
}
