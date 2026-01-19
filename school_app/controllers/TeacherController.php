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

        // 1. Get current school year and active term
        $yearStmt = $db->query("SELECT * FROM school_years WHERE is_current = 1 LIMIT 1");
        $currentYear = $yearStmt->fetch(PDO::FETCH_OBJ);
        $schoolYearId = $currentYear ? $currentYear->id : 0;

        $currentTerm = Term::getCurrent();

        // 2. Summary stats for the teacher
        // Current Classes Count (Classes with active enrollments in current year)
        $stmt = $db->prepare("
            SELECT COUNT(DISTINCT e.class_id) as count 
            FROM enrollments e 
            JOIN classes c ON e.class_id = c.id
            WHERE c.is_active = 1 AND e.school_year_id = ? AND e.status = 'ACTIVE'
        ");
        $stmt->execute([$schoolYearId]);
        $myClassesCount = $stmt->fetch(PDO::FETCH_OBJ)->count;

        // Current Students Count (Current Year)
        $stmt = $db->prepare("
            SELECT COUNT(DISTINCT s.id) as count
            FROM students s
            JOIN enrollments e ON s.id = e.student_id
            WHERE s.is_active = 1 AND e.status = 'ACTIVE' AND e.school_year_id = ?
        ");
        $stmt->execute([$schoolYearId]);
        $myStudentsCount = $stmt->fetch(PDO::FETCH_OBJ)->count;

        // 3. Get detailed classes list (matching table in image)
        // Only show classes that have active enrollments this year
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

        foreach ($classes as $class) {
            // Student count for this year
            $stmt = $db->prepare("
                SELECT COUNT(*) as count 
                FROM enrollments e 
                WHERE e.class_id = ? AND e.status = 'ACTIVE' AND e.school_year_id = ?
            ");
            $stmt->execute([$class->id, $schoolYearId]);
            $class->student_count = $stmt->fetch(PDO::FETCH_OBJ)->count;

            // Primary Subject (inferred from grade)
            $stmt = $db->prepare("SELECT name FROM subjects WHERE grade_id = ? LIMIT 1");
            $stmt->execute([$class->grade_id]);
            $subj = $stmt->fetch(PDO::FETCH_OBJ);
            $class->primary_subject = $subj ? $subj->name : 'N/A';
        }

        // 4. Get recent score entries by this teacher
        $stmt = $db->prepare("
            SELECT 
                sc.*,
                s.first_name, s.last_name,
                sub.name as subject_name
            FROM scores sc
            JOIN enrollments e ON sc.enrollment_id = e.id
            JOIN students s ON e.student_id = s.id
            JOIN subjects sub ON sc.subject_id = sub.id
            WHERE sc.teacher_user_id = ?
            ORDER BY sc.created_at DESC
            LIMIT 5
        ");
        $stmt->execute([$teacherId]);
        $recentScores = $stmt->fetchAll(PDO::FETCH_OBJ);

        return [
            'currentYear' => $currentYear,
            'currentTerm' => $currentTerm,
            'myClassesCount' => $myClassesCount,
            'myStudentsCount' => $myStudentsCount,
            'classes' => $classes,
            'recentScores' => $recentScores
        ];
    }

    public function classes()
    {
        $db = Database::connect();

        // Get current school year
        $yearStmt = $db->query("SELECT id FROM school_years WHERE is_current = 1 LIMIT 1");
        $currentYear = $yearStmt->fetch(PDO::FETCH_OBJ);
        $schoolYearId = $currentYear ? $currentYear->id : 0;

        // Only show classes that have active enrollments this year
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

        foreach ($classes as $class) {
            // Fix: Filter by current school year for accurate counts
            $stmt = $db->prepare("
                SELECT COUNT(*) as count 
                FROM enrollments e 
                WHERE e.class_id = ? AND e.status = 'ACTIVE' AND e.school_year_id = ?
            ");
            $stmt->execute([$class->id, $schoolYearId]);
            $class->student_count = $stmt->fetch(PDO::FETCH_OBJ)->count;

            // Get the primary subject taught in this class (or just count grade-level subjects)
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM subjects WHERE grade_id = ?");
            $stmt->execute([$class->grade_id]);
            $class->subject_count = $stmt->fetch(PDO::FETCH_OBJ)->count;

            // For the new UI: Get some example subjects
            $stmt = $db->prepare("SELECT name FROM subjects WHERE grade_id = ? LIMIT 1");
            $stmt->execute([$class->grade_id]);
            $subj = $stmt->fetch(PDO::FETCH_OBJ);
            $class->primary_subject = $subj ? $subj->name : 'General';
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
        $termId = $_GET['term_id'] ?? null;

        if (!$classId) {
            echo json_encode(['error' => 'Class ID required']);
            exit;
        }

        $db = Database::connect();
        $schoolYearId = null;

        if ($termId) {
            $stmt = $db->prepare("SELECT school_year_id FROM terms WHERE id = ?");
            $stmt->execute([$termId]);
            $term = $stmt->fetch(PDO::FETCH_OBJ);
            if ($term) {
                $schoolYearId = $term->school_year_id;
            }
        }

        $students = Student::getByClass($classId, $schoolYearId);
        echo json_encode(['students' => $students]);
        exit;
    }
}
