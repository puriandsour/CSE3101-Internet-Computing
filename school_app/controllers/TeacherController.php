<?php
// controllers/TeacherController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Score.php';

class TeacherController
{
    /**
     * Display the list of teachers (Admin)
     */
    public function index()
    {
        $search = $_GET['search'] ?? '';

        // Fetch users with role 'TEACHER'
        $teachers = User::getAll([
            'role' => 'TEACHER',
            'search' => $search
        ]);

        return [
            'teachers' => $teachers,
            'filters' => [
                'search' => $search
            ]
        ];
    }

    /**
     * Show the form to add a new teacher (Admin)
     */
    public function add()
    {
        return render_view('views/admin/teachers/add.php');
    }

    /**
     * Handle the POST request to create a teacher (Admin)
     */
    public function create($data)
    {
        // 1. Validate Password presence
        if (empty($data['password'])) {
            $_SESSION['error'] = "Password is required.";
            header("Location: index.php?controller=teacher&action=add");
            exit;
        }

        // 2. Find the Teacher Role
        $role = Role::findByName('TEACHER');
        if (!$role) {
            $_SESSION['error'] = "System Error: Teacher role not found.";
            header("Location: index.php?controller=teacher&action=add");
            exit;
        }

        // 3. Prepare User Data for User::save()
        $userData = [
            $data['username'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['first_name'],
            $data['last_name'],
            1 // is_active
        ];

        $userModel = new User();
        $userId = $userModel->save($userData, $role->id);

        if ($userId) {
            $_SESSION['success'] = "Teacher added successfully.";
            header("Location: index.php?controller=teacher&action=index");
            exit;
        } else {
            header("Location: index.php?controller=teacher&action=add");
            exit;
        }
    }

    /**
     * Teacher Dashboard
     */
    public function dashboard()
    {
        $teacherId = $_SESSION['user_id'];
        $db = Database::connect();

        // Get teacher's classes (simplified for now, counts all active classes as a baseline)
        $stmt = $db->query("SELECT COUNT(DISTINCT c.id) as count FROM classes c WHERE c.is_active = 1");
        $myClasses = $stmt->fetch(PDO::FETCH_OBJ)->count;

        // Get total students
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

    /**
     * Teacher Classes List
     */
    public function classes()
    {
        $classes = ClassModel::getAll();
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

    /**
     * View specific class (Teacher)
     */
    public function viewClass($classId)
    {
        $class = ClassModel::find($classId);
        if (!$class) {
            $_SESSION['error'] = "Class not found.";
            header("Location: index.php?controller=teacher&action=classes");
            exit;
        }

        $students = Student::getByClass($classId);

        return [
            'class' => $class,
            'students' => $students
        ];
    }

    /**
     * AJAX: Get students for a class
     */
    public function getClassStudents()
    {
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
