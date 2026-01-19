<?php
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Score.php';

class TeacherController {
    
    public function dashboard() {
        $teacherId = $_SESSION['user_id'];
        $db = Database::connect();
        
        // Get teacher's classes
        $stmt = $db->query("SELECT COUNT(DISTINCT c.id) as count FROM classes c WHERE c.is_active = 1");
        $myClasses = $stmt->fetch(PDO::FETCH_OBJ)->count;
        
        // Get total students in teacher's classes
        $stmt = $db->query("
            SELECT COUNT(DISTINCT s.id) as count
            FROM students s
            JOIN enrollments e ON s.id = e.student_id
            WHERE s.is_active = 1 AND e.status = 'ACTIVE'
        ");
        $myStudents = $stmt->fetch(PDO::FETCH_OBJ)->count;
        
        // Get scores entered by this teacher
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM scores WHERE teacher_user_id = ?");
        $stmt->execute([$teacherId]);
        $scoresEntered = $stmt->fetch(PDO::FETCH_OBJ)->count;
        
        // Get teacher's classes with details
        $classes = ClassModel::getAll();
        
        return [
            'myClasses' => $myClasses,
            'myStudents' => $myStudents,
            'scoresEntered' => $scoresEntered,
            'classes' => $classes
        ];
    }
    
    public function classes() {
        // Get all classes (in production, filter by teacher)
        $classes = ClassModel::getAll();
        
        // Add student count for each class
        $db = Database::connect();
        foreach ($classes as $class) {
            $stmt = $db->prepare("
                SELECT COUNT(*) as count 
                FROM enrollments e 
                WHERE e.class_id = ? AND e.status = 'ACTIVE'
            ");
            $stmt->execute([$class->id]);
            $class->student_count = $stmt->fetch(PDO::FETCH_OBJ)->count;
            
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM subjects WHERE grade_id = ?");
            $stmt->execute([$class->grade_id]);
            $class->subject_count = $stmt->fetch(PDO::FETCH_OBJ)->count;
        }
        
        return ['classes' => $classes];
    }
    
    public function viewClass($classId) {
        $class = ClassModel::find($classId);
        if (!$class) {
            $_SESSION['error'] = "Class not found.";
            header("Location: index.php?controller=teacher&action=classes");
            exit;
        }
        
        // Get students in this class
        $students = Student::getByClass($classId);
        
        return [
            'class' => $class,
            'students' => $students
        ];
    }
    
    public function getClassStudents() {
        header('Content-Type: application/json');
        
        $classId = $_GET['class_id'] ?? null;
        
        if (!$classId) {
            echo json_encode(['error' => 'Class ID required']);
            exit;
        }
        
        $students = Student::getByClass($classId);
        
        echo json_encode(['students' => $students]);
        exit;
    }
}
