<?php
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Grade.php';

class ClassController {
    
    public function index() {
        $classes = ClassModel::getAll();
        return ['classes' => $classes];
    }
    
    public function add() {
        // Just render the form - no data needed yet
    }
    
    public function create($data) {
        if (empty($data['name']) || empty($data['grade_id'])) {
            $_SESSION['error'] = "Class name and grade are required.";
            header("Location: index.php?controller=class&action=add");
            exit;
        }
        
        $class = new ClassModel();
        $classId = $class->create([
            'name' => $data['name'],
            'grade_id' => $data['grade_id'],
            'room' => $data['room'] ?? null
        ]);
        
        if ($classId) {
            $_SESSION['success'] = "Class added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add class.";
        }
        
        header("Location: index.php?controller=class");
        exit;
    }
    
    public function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE classes SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Class deleted successfully!";
        header("Location: index.php?controller=class");
        exit;
    }
}
