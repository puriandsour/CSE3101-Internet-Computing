<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Grade.php';
require_once __DIR__ . '/../models/Enrollment.php';

class StudentController {
    
    public function index() {
        // Get all students with their class info
        $students = Student::getAll(['is_active' => 1]);
        
        // Get filters for dropdowns
        $grades = Grade::getAll();
        $classes = ClassModel::getAll();
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'grade_id' => $_GET['grade_id'] ?? '',
            'class_id' => $_GET['class_id'] ?? ''
        ];
        
        $pagination = [
            'page' => $_GET['page'] ?? 1,
            'total' => count($students),
            'total_pages' => 1
        ];
        
        return [
            'students' => $students,
            'grades' => $grades,
            'classes' => $classes,
            'filters' => $filters,
            'pagination' => $pagination
        ];
    }
    
    public function add() {
        // Just render the add form
        header("Location: index.php?controller=student&action=create_form");
    }
    
    public function create($data) {
        if (empty($data['admission_no']) || empty($data['first_name']) || empty($data['last_name'])) {
            $_SESSION['error'] = "Required fields missing.";
            header("Location: index.php?controller=student&action=add");
            exit;
        }
        
        $student = new Student();
        $studentId = $student->create([
            'admission_no' => $data['admission_no'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null
        ]);
        
        if ($studentId) {
            $_SESSION['success'] = "Student added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add student.";
        }
        
        header("Location: index.php?controller=student");
        exit;
    }
    
    public function enroll() {
        $studentId = $_GET['id'] ?? null;
        
        if (!$studentId) {
            $_SESSION['error'] = "Student not found.";
            header("Location: index.php?controller=student");
            exit;
        }
        
        // Get student
        $student = Student::findById($studentId);
        
        if (!$student) {
            $_SESSION['error'] = "Student not found.";
            header("Location: index.php?controller=student");
            exit;
        }
        
        // Get available classes and school years
        $classes = ClassModel::getAll();
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM school_years ORDER BY id DESC");
        $schoolYears = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return [
            'student' => $student,
            'classes' => $classes,
            'schoolYears' => $schoolYears
        ];
    }
    
    public function processEnrollment($data) {
        if (empty($data['student_id']) || empty($data['class_id']) || empty($data['school_year_id'])) {
            $_SESSION['error'] = "All fields required.";
            header("Location: index.php?controller=student&action=enroll&id=" . ($data['student_id'] ?? ''));
            exit;
        }
        
        $db = Database::connect();
        
        // Deactivate any existing enrollments for this student
        $stmt = $db->prepare("UPDATE enrollments SET status = 'INACTIVE' WHERE student_id = ?");
        $stmt->execute([$data['student_id']]);
        
        // Create new enrollment
        $stmt = $db->prepare("
            INSERT INTO enrollments (student_id, class_id, school_year_id, status, created_at)
            VALUES (?, ?, ?, 'ACTIVE', NOW())
        ");
        $stmt->execute([
            $data['student_id'],
            $data['class_id'],
            $data['school_year_id']
        ]);
        
        $_SESSION['success'] = "Student enrolled successfully!";
        header("Location: index.php?controller=student");
        exit;
    }
}
