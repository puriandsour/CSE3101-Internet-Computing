<?php
require_once __DIR__ . '/../models/Score.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Enrollment.php';

class ScoreController {
    
    public function enter() {
        $classes = ClassModel::getAll();
        $terms = Term::getAll();
        $subjects = Subject::getAll();
        
        return [
            'classes' => $classes,
            'terms' => $terms,
            'subjects' => $subjects
        ];
    }
    
    public function manage() {
        // Just render the manage view - data loaded in view
    }
    
    public function getStudents() {
        header('Content-Type: application/json');
        
        $classId = $_GET['class_id'] ?? null;
        $termId = $_GET['term_id'] ?? null;
        $subjectId = $_GET['subject_id'] ?? null;
        
        if (!$classId || !$termId || !$subjectId) {
            echo json_encode(['error' => 'Missing parameters']);
            exit;
        }
        
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT 
                s.*,
                e.id as enrollment_id,
                sc.score as current_score,
                sc.remarks
            FROM students s
            JOIN enrollments e ON s.id = e.student_id
            LEFT JOIN scores sc ON e.id = sc.enrollment_id 
                AND sc.subject_id = ? 
                AND sc.term_id = ?
            WHERE e.class_id = ? 
                AND e.status = 'ACTIVE'
                AND s.is_active = 1
            ORDER BY s.admission_no
        ");
        
        $stmt->execute([$subjectId, $termId, $classId]);
        $students = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        echo json_encode(['students' => $students]);
        exit;
    }
    
    public function save() {
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
    
    public function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM scores WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Score deleted successfully!";
        header("Location: index.php?controller=score&action=manage");
        exit;
    }
}
