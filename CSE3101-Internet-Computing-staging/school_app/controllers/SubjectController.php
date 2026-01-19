<?php
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Grade.php';

class SubjectController {
    
    public function index() {
        $subjects = Subject::getAll();
        return ['subjects' => $subjects];
    }
    
    public function add() {
        // Just render the form
    }
    
    public function create($data) {
        if (empty($data['name']) || empty($data['grade_id'])) {
            $_SESSION['error'] = "Subject name and grade are required.";
            header("Location: index.php?controller=subject&action=add");
            exit;
        }
        
        $subject = new Subject();
        $subjectId = $subject->create([
            'name' => $data['name'],
            'grade_id' => $data['grade_id'],
            'code' => $data['code'] ?? null
        ]);
        
        if ($subjectId) {
            $_SESSION['success'] = "Subject added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add subject.";
        }
        
        header("Location: index.php?controller=subject");
        exit;
    }
    
    public function delete($id) {
        $db = Database::connect();
        
        // Check if subject has scores
        $checkStmt = $db->prepare("SELECT COUNT(*) as count FROM scores WHERE subject_id = ?");
        $checkStmt->execute([$id]);
        $result = $checkStmt->fetch(PDO::FETCH_OBJ);
        
        if ($result->count > 0) {
            $_SESSION['error'] = "Cannot delete subject - it has associated scores. Delete scores first.";
        } else {
            $stmt = $db->prepare("DELETE FROM subjects WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success'] = "Subject deleted successfully!";
        }
        
        header("Location: index.php?controller=subject");
        exit;
    }
}
